FROM php:7.1.19-cli

LABEL antarian-dockerfile=Antier

RUN apt-get update && \
    apt-get install -y \
        libzip-dev \
        zip \
    && docker-php-ext-configure zip --with-libzip \
    && docker-php-ext-install zip \
    && apt-get clean

RUN mkdir /usr/src/php
RUN tar --file /usr/src/php.tar.xz --extract --strip-components=1 --directory /usr/src/php
RUN cp /usr/src/php/php.ini-production /usr/local/etc/php/php.ini

RUN mkdir -p /usr/src/antier;

WORKDIR /usr/src/antier

COPY ./ /usr/src/antier

# CMD [ "php", "./index.php" ]

