# Etapa de construcción
FROM node:14 AS build

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci --production

COPY . .
RUN npm run build

# Etapa de producción
FROM php:8.0-apache

WORKDIR /var/www/html

COPY --from=build /app/public/build /var/www/html/public/build
COPY . .

RUN composer install --no-dev --optimize-autoloader
RUN php artisan optimize
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache
RUN php artisan migrate --force
