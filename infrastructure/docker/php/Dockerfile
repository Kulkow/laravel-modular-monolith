FROM php:8.4-fpm-alpine

# Установка системных зависимостей и инструментов сборки
RUN apk add --no-cache \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    zip \
    curl \
    nodejs \
    npm \
    oniguruma-dev \
    autoconf \
    build-base

# Установка расширений PHP
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    pgsql \
    zip \
    bcmath \
    mbstring \
    exif \
    pcntl

# Установка Redis через PECL (с предварительной установкой autoconf/build-base)
RUN pecl install redis && docker-php-ext-enable redis

# Удаление инструментов сборки (для уменьшения размера образа)
RUN apk del autoconf build-base

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Копирование кода приложения (если сборка для продакшена)
# Для локальной разработки код монтируется через volume, поэтому COPY можно закомментировать
# COPY --chown=www-data:www-data application /var/www/html

# Права доступа (если выполняется COPY)
# RUN chown -R www-data:www-data /var/www/html \
#     && chmod -R 755 /var/www/html/storage \
#     && chmod -R 755 /var/www/html/bootstrap/cache

USER www-data

EXPOSE 9000

CMD ["php-fpm"]
