FROM webdevops/php-nginx-dev:8.1-alpine

COPY ./app /app

WORKDIR /app

RUN curl -sS https://get.symfony.com/cli/installer | bash

ENV PATH="/root/.symfony5/bin:${PATH}"
