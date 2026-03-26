FROM php:8.2-fpm-alpine AS base

RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    zip \
    unzip \
    git \
    nodejs \
    npm \
    libpng-dev \
    && docker-php-ext-install pdo pdo_mysql opcache gd pcntl

# Redis PHP extension
RUN apk add --no-cache $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del $PHPIZE_DEPS

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# ---- deps ----
FROM base AS deps

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

COPY package.json package-lock.json* ./
RUN npm ci

# ---- final ----
FROM base AS final

COPY --from=deps /var/www/html/vendor ./vendor
COPY --from=deps /var/www/html/node_modules ./node_modules
COPY . .

RUN npm run build && rm -rf node_modules

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

COPY .docker/nginx/default.conf /etc/nginx/http.d/default.conf
COPY .docker/supervisord.conf /etc/supervisord.conf
COPY .docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
