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


FROM gkephart/alpine-php-apache

# copy vendor into the project from the prebuild stage
COPY --from=vendor /app/vendor/ /app/vendor

WORKDIR /var/www

RUN ln -s /app/vendor

COPY ./Classes /app/Classes
COPY ./lib /app/lib
COPY public_html/ /app/public

# configure Secrets for protected information

WORKDIR /etc/apache2
RUN mkdir capstone-mysql
COPY ./secrets ./capstone-mysql
RUN touch ./capstone-mysql/ddctwitter.ini && php ./capstone-mysql/ddctwitter.ini


EXPOSE 80