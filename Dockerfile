FROM php:8.3

RUN apt-get update && apt-get install -y \
    bash \
    git \
    nano \
    htop \
    fish \
    libpq-dev \
    postgresql-client \
    zip \
    wget \
    && rm -rf /var/lib/apt/lists/*

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions

RUN install-php-extensions pdo_pgsql
RUN install-php-extensions bcmath
RUN install-php-extensions pcntl

RUN install-php-extensions @composer

EXPOSE 8000

CMD ["php","artisan","octane:start","--host","0.0.0.0","--workers","4"]
