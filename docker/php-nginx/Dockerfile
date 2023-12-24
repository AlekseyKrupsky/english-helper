FROM webdevops/php-nginx-dev:8.1-alpine

COPY ./app /app

WORKDIR /app

RUN url -sS https://get.symfony.com/cli/installer | bash

export PATH="$HOME/.symfony5/bin:$PATH"
