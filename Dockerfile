FROM dunglas/frankenphp:php8.2

ENV SERVER_NAME=:80

WORKDIR /app

COPY . .

# Install ekstensi PHP yang dibutuhkan Laravel
RUN apt update && apt install -y \
    zip libzip-dev \
    libpng-dev libonig-dev libpq-dev \
    git unzip curl \
    && docker-php-ext-install zip gd pdo pdo_mysql \
    && docker-php-ext-enable zip gd pdo_mysql

# Copy composer dari image resmi composer
COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer

# Install dependency (untuk dev environment jangan pakai --no-dev)
RUN composer install --ignore-platform-reqs --optimize-autoloader

# Set permission
RUN chown -R www-data:www-data storage bootstrap/cache
