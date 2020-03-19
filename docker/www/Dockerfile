FROM php:7.1-apache

ARG XDEBUG_REMOTE_HOST=172.17.0.1
ARG XDEBUG_IDEKEY=PHPSTORM

RUN apt-get update -y
RUN apt-get install -y --no-install-recommends libzip-dev zip unzip git libfreetype6-dev libjpeg62-turbo-dev libpng-dev

RUN docker-php-ext-install mysqli calendar mbstring
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ && docker-php-ext-install -j$(nproc) gd
RUN pecl install xdebug && docker-php-ext-enable xdebug && \
    echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.remote_host=$XDEBUG_REMOTE_HOST" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.idekey=$XDEBUG_IDEKEY" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN curl -o /usr/local/bin/composer https://getcomposer.org/composer.phar && chmod +x /usr/local/bin/composer

COPY forum.conf /etc/apache2/sites-available/
RUN a2enmod rewrite
RUN a2dissite 000-default && a2ensite forum.conf

RUN usermod -u 1000 www-data
RUN chown www-data /var/www
