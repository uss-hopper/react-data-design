# prestage build used for handling composer
FROM composer:1.7 as vendor

COPY composer.json composer.json
COPY composer.lock composer.lock

RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist

# main build stage
#FROM webdevops/php-apache:alpine

#Development image for webdevops/php-apache
FROM webdevops/php-apache-dev

# copy vendor into the project from the prebuild stage
COPY --from=vendor /app/vendor/ /var/www/vendor/

RUN mkdir /etc/apache2/capstone-mysql
COPY config/secrets /etc/apache2/capstone-mysql
RUN touch /etc/apache2/capstone-mysql/ddctwitter.ini && php /etc/apache2/capstone-mysql/ddctwitter.php

# configure Secrets for protected information
WORKDIR /var/www

EXPOSE 80






