FROM php:8.3-alpine

RUN apk update && apk add \
    bash \
    git \
    nano \
    htop \
    fish \
    libpq-dev \
    postgresql-client \
    zip \
    wget

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions

RUN install-php-extensions pdo_pgsql
RUN install-php-extensions bcmath
RUN install-php-extensions pcntl
RUN install-php-extensions rdkafka
RUN install-php-extensions sockets

RUN install-php-extensions @composer

EXPOSE 8000

CMD ["php","artisan","octane:start","--host","0.0.0.0","--workers","4"]
