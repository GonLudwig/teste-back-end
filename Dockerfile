FROM php:8.1-fpm-alpine

RUN adduser -g 'Nginx www user' -h /var/www/ wwwcbz -D

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY ./docker/nginx/defaulf.conf /etc/nginx/http.d/default.conf
COPY ./docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./docker/php/www.conf /usr/local/etc/php-fpm.d/www.conf
COPY ./docker/php/php.ini /usr/local/etc/php/conf.d/php.ini
COPY --chown=wwwcbz:wwwcbz . /var/www

WORKDIR /var/www

RUN apk update \
    && apk add --no-cache \
    nginx \
    libpng \
    libpng-dev \
    curl-dev \
    redis \
    && docker-php-ext-configure gd \
    && docker-php-ext-install pdo pdo_mysql mysqli exif pcntl \
    && chmod -R 777 /var/www/storage/ \
    && composer install \
    && php artisan key:generate

EXPOSE 80

CMD ["/bin/ash", "-c", "php-fpm -D && nginx -g 'daemon off;'"]