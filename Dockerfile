FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN mkdir -p storage/framework/{sessions,views,cache} \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 10000


RUN php artisan view:clear || true
RUN php artisan cache:clear || true
RUN php artisan config:clear || true


RUN apt-get update && apt-get install -y curl

RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash -

RUN apt-get install -y nodejs

# ACTUAL
# CMD php artisan storage:link && touch database/database.sqlite && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-10000}

# SEED
# CMD php artisan storage:link && touch database/database.sqlite && php artisan migrate --force && php artisan db:seed --force && php artisan serve --host=0.0.0.0 --port=${PORT:-10000}


# CMD php artisan storage:link && php artisan migrate --force && php artisan db:seed --class=TestUserSeeder --force && php artisan serve --host=0.0.0.0 --port=${PORT:-10000}
# CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-10000}
