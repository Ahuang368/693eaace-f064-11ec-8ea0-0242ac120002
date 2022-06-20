FROM php:8.0-apache

RUN apt-get update -yqq \
    && apt-get install git zlib1g-dev libsqlite3-dev unzip -y

RUN curl -fsSL https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && composer global require phpunit/phpunit ^9.5 --no-progress --no-scripts --no-interaction

ENV PATH /root/.composer/vendor/bin:$PATH

EXPOSE 80