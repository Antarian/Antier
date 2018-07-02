# development docker file
FROM php:7.1.19-cli

LABEL antarian-dockerfile=Antier

# install git, zip and vim
RUN apt-get update && \
    apt-get install -y \
        git \
        libzip-dev \
        zip \
        vim \
        libssl-dev \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-configure zip --with-libzip \
    && docker-php-ext-install zip \
    && docker-php-ext-install pcntl \
    && apt-get clean

# prepare php ini settings
RUN mkdir /usr/src/php
RUN tar --file /usr/src/php.tar.xz --extract --strip-components=1 --directory /usr/src/php
RUN cp /usr/src/php/php.ini-development /usr/local/etc/php/php.ini

# prepare workdir
RUN mkdir -p /usr/src/antier;
WORKDIR /usr/src/antier

# install composer
RUN mkdir -p /usr/src/antier/vendor/bin;
RUN curl -s https://getcomposer.org/installer | php && \
    echo "export PATH=${PATH}:/usr/src/antier/vendor/bin" > ~/.bashrc && \
    mv composer.phar /usr/local/bin/composer
RUN . ~/.bashrc

RUN pecl install mongodb-1.5.0 \
    && docker-php-ext-enable mongodb
