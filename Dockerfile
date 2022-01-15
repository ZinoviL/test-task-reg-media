FROM php:7.2-fpm

RUN apt-get update && apt-get install -y git zip
RUN docker-php-ext-install pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ARG USER_ID
ARG GROUP_ID

RUN groupadd -r app -g ${GROUP_ID} && \
    useradd  -u ${USER_ID} -r -g app -s /sbin/nologin app

USER app

WORKDIR /var/www