# Etapa de construcción
FROM node:14 as build

WORKDIR /app

COPY package*.json ./
RUN npm install

COPY . .
RUN npm run build

# Etapa de producción
FROM php:8.1-fpm

WORKDIR /app

COPY --from=build /app/public/build /app/public/build
COPY --from=build /app/resources /app/resources
COPY --from=build /app/vendor /app/vendor
COPY --from=build /app/composer.lock /app/composer.lock
COPY --from=build /app/composer.json /app/composer.json

RUN composer install --optimize-autoloader --no-dev
RUN php artisan optimize

CMD ["php-fpm"]

