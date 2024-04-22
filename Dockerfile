FROM dunglas/frankenphp

ARG NODE_VERSION=20

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

RUN install-php-extensions \
    pdo_mysql \
    gd \
    intl \
    zip \
    opcache \
    redis \
    memcached \
    intl \
    exif \ 
    pcntl 

WORKDIR /var/www

ADD . .

RUN composer install

RUN curl -fsSL https://deb.nodesource.com/setup_lts.x | bash -
RUN apt-get install -y nodejs
RUN npm install -g bun

RUN bun install
RUN bun run build

RUN touch storage/logs/laravel.log

RUN php artisan octane:install --server=frankenphp -n

EXPOSE 8000

ENTRYPOINT php artisan octane:start --host 0.0.0.0 --port 8000