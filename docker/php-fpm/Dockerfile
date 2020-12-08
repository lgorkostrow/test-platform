FROM php:7.4-fpm-alpine

COPY wait-for-it.sh /usr/bin/wait-for-it

RUN chmod +x /usr/bin/wait-for-it
RUN apk --update --no-cache add git
RUN docker-php-ext-install pdo_mysql
RUN apk add --no-cache libpng libpng-dev && docker-php-ext-install gd && apk del libpng-dev

# Extensions
RUN echo "Extensions install - start" \
    # Sockets
    && docker-php-ext-install sockets \
    # Amqp
    && apk add --no-cache --update rabbitmq-c-dev \
    && apk add --no-cache --update --virtual .phpize-deps $PHPIZE_DEPS \
    && pecl install -o -f amqp \
    && docker-php-ext-enable amqp \
    && apk del .phpize-deps \
    #
    && echo "Extensions install - finish"

COPY --from=composer /usr/bin/composer /usr/bin/composer
CMD composer install ; wait-for-it database:3306 -- bin/console doctrine:migrations:migrate ;  php-fpm

COPY --chown=www-data:www-data . /var/www

WORKDIR /var/www

EXPOSE 9000
