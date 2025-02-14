FROM php:8.4-fpm-alpine

RUN apk add nginx supervisor sqlite-dev curl-dev

RUN docker-php-ext-install pdo_sqlite
RUN docker-php-ext-install curl

COPY build/default.conf /etc/nginx/http.d/default.conf
COPY build/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY build/supervisord.conf /etc/supervisord.conf

COPY ./ /var/www/html

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
WORKDIR /var/www/html

RUN composer install

ENTRYPOINT ["build/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]

EXPOSE 80