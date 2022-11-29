<?php

namespace Drupal\decoupled_router\EventSubscriber;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Cache\CacheableJsonResponse;
use Drupal\Core\GeneratedUrl;
use Drupal\Core\Url;
use Drupal\decoupled_router\PathTranslatorEvent;

/**
 * Event subscriber that processes a path translation with the redirect info.
 */
class RedirectPathTranslatorSubscriber extends RouterPathTranslatorSubscriber {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    // We wanna run before the router-based path translator because redirects
    // naturally act before routing subsystem in Drupal HTTP kernel.
    $events[PathTranslatorEvent::TRANSLATE][] = ['onPathTranslation', 10];
    return $events;
  }

  /**
   * {@inheritdoc}
   */
  public function onPathTranslation(PathTranslatorEvent $event) {
    $response = $event->getResponse();
    if (!$response instanceof CacheableJsonResponse) {
      $this->logger->error('Unable to get the response object for the decoupled router event.');
      return;
    }
    if (!$this->moduleHandler->moduleExists('redirect')) {
      return;
    }
    // Find the redirected path. Bear in mind that we need to go through several
    // redirection levels before handing off to the route translator.
    $entity_type_manager = $this->container->get('entity_type.manager');
    $redirect_storage = $entity_type_manager->getStorage('redirect');
    $destination = parse_url($event->getPath(), PHP_URL_PATH);
    $original_query_string = parse_url($event->getPath(), PHP_URL_QUERY);
    $traced_urls = [];
    $redirect = NULL;
    $redirects_trace = [];
    while (TRUE) {
      $destination = $this->cleanSubdirInPath($destination, $event->getRequest());
      // Find if there is a redirect for this path.
      $results = $redirect_storage
        ->getQuery()
        // Redirects are stored without the leading slash :-(.
        ->condition('redirect_source.path', ltrim($destination, '/'))
        ->execute();
      $rid = reset($results);
      if (!$rid) {
        break;
      }
      /** @var \Drupal\redirect\Entity\Redirect $redirect */
      $redirect = $redirect_storage->load($rid);
      $response->addCacheableDependency($redirect);
      $uri = $redirect->get('redirect_redirect')->uri;
      $url = Url::fromUri($uri)->toString(TRUE);
      $redirects_trace[] = [
        'from' => $this->makeRedirectUrl($destination, $original_query_string),
        'to' => $this->makeRedirectUrl($url->getGeneratedUrl(), $original_query_string),
        'status' => $redirect->getStatusCode(),
      ];
      $destination = $url->getGeneratedUrl();

      // Detect infinite loops and break if there is one.
      $infinite_loop = in_array($destination, array_map(function (GeneratedUrl $url) {
        return $url->getGeneratedUrl();
      }, $traced_urls));
      // Accumulate all the URLs we go through to add the necessary cacheability
      // metadata at the end.
      $traced_urls[] = $url;
      if ($infinite_loop) {
        break;
      }
    }
    if (!$redirect) {
      return;
    }
    // At this point we should be pointing to a system route or path alias.
    $event->setPath($this->makeRedirectUrl($destination, $original_query_string));

    // Now call the route level.
    parent::onPathTranslation($event);

    if (!$response->isSuccessful()) {
      return;
    }
    // Set the content in the response.
    $content = Json::decode($response->getContent());
    $response->setData(array_merge(
      $content,
      ['redirect' => $redirects_trace]
    ));
    // If there is a response object, add the cacheability metadata necessary
    // for the traced URLs.
    array_walk($traced_urls, function ($traced_url) use ($response) {
      $response->addCacheableDependency($traced_url);
    });
    $event->stopPropagation();
  }

  /**
   * Generates URL for the redirect, based on redirect module configurations.
   *
   * @param string $path
   *   URL to redirect to.
   * @param string $query
   *   Original query string on the requested path.
   *
   * @return string
   *   Redirect URL to use.
   */
  private function makeRedirectUrl($path, $query) {
    return $query && $this->configFactory->get('redirect.settings')
      ->get('passthrough_querystring')
      ? "{$path}?{$query}"
      : $path;
  }

}
