# syntax=docker/dockerfile:1

# ---- Stage 1: build Tailwind CSS ----
FROM node:20-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY tailwind.config.js ./
COPY resources ./resources
COPY templates ./templates
COPY public/assets/app.js ./public/assets/app.js
RUN npm run build

# ---- Stage 2: PHP dependencies (prod = no dev tooling, dev = incl. PHPUnit/PHPStan) ----
FROM composer:2 AS vendor-base
WORKDIR /app
COPY composer.json composer.lock ./

FROM vendor-base AS vendor-prod
RUN composer install --no-dev --no-interaction --no-progress --prefer-dist --optimize-autoloader --no-scripts

FROM vendor-base AS vendor-dev
RUN composer install --no-interaction --no-progress --prefer-dist --no-scripts

# ---- Stage 3: base runtime (Apache + PHP) ----
FROM php:8.3-apache AS base
RUN a2enmod rewrite \
 && echo 'ServerName localhost' > /etc/apache2/conf-available/servername.conf && a2enconf servername \
 && sed -ri 's!/var/www/html!/var/www/public!g' /etc/apache2/sites-available/*.conf /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
WORKDIR /var/www
COPY src ./src
COPY templates ./templates
COPY public ./public
COPY data ./data
COPY --from=assets /app/public/assets/app.css ./public/assets/app.css

# ---- Stage 4: production image ----
FROM base AS production
COPY --from=vendor-prod /app/vendor ./vendor
EXPOSE 80
HEALTHCHECK --interval=30s --timeout=3s CMD php -r 'exit(@file_get_contents("http://localhost/") === false ? 1 : 0);'

# ---- Stage 5: test runner ----
FROM base AS test
COPY --from=vendor-dev /app/vendor ./vendor
COPY composer.json composer.lock phpunit.xml phpstan.neon ./
COPY tests ./tests
CMD ["vendor/bin/phpunit"]
