FROM php:8.3-fpm-bullseye

RUN apt-get update && apt-get install -y \
    git curl zip unzip libpq-dev libonig-dev libxml2-dev libzip-dev \
    libpng-dev libjpeg-dev libfreetype6-dev libicu-dev libcurl4-openssl-dev \
    supervisor \
    && docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl intl gd \
    && pecl install redis && docker-php-ext-enable redis

RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY docker/php/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

WORKDIR /var/www/html

CMD ["/usr/bin/supervisord", "-n"]
