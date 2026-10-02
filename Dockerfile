FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    libpq-dev \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_pgsql pdo_sqlite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN mkdir -p storage/framework/{sessions,views,cache} database \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 10000

RUN php artisan view:clear || true
RUN php artisan cache:clear || true
RUN php artisan config:clear || true

RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Build Vite frontend assets
RUN npm install && npm run build

# Output Laravel logs directly to Render's log stream
ENV LOG_CHANNEL=stderr

RUN chmod +x docker/entrypoint.sh

CMD ["sh", "docker/entrypoint.sh"]
