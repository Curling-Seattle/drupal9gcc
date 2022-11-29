FROM drupal:latest

WORKDIR /opt/drupal
RUN composer update "drupal/core-*" --with-all-dependencies

# I was unable to get the Drupal setup to run if the drupal/next
# modules were loaded first. It seems that the following composer
# commmand must be run after the Druap site is initialized
# RUN composer require drupal/next
