# syntax=docker/dockerfile:1

ARG PHP_VERSION=unset
ARG COMPOSER_VERSION=unset

FROM php:${PHP_VERSION}-cli-alpine

# Packages & Extensions
# See https://github.com/mlocati/docker-php-extension-installer
ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN install-php-extensions \
      @composer-${COMPOSER_VERSION} \
      pcntl \
      xdebug \
    && echo "Complete"
