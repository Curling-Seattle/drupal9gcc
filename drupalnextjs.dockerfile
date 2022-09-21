# syntax=docker/dockerfile:1

FROM drupal:latest
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash -
RUN apt-get install -y nodejs
RUN composer config --no-plugins allow-plugins.cweagans/composer-patches true
RUN composer require drupal/next
RUN cd /opt/drupal/web/core ; \
   composer config --no-plugins allow-plugins.cweagans/composer-patches true ; \
   composer require cweagans/composer-patches

