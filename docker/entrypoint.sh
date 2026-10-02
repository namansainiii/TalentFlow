#!/bin/sh
set -e

echo "=== Starting TalentFlow Container ==="

# 1. Ensure .env file exists
if [ ! -f .env ]; then
    echo "Creating .env from .env.example..."
    cp .env.example .env
fi

# 2. Ensure APP_KEY exists
if [ -n "$APP_KEY" ]; then
    echo "APP_KEY provided via environment."
    # Write the environment APP_KEY into .env as well so artisan commands and web server have it
    sed -i "s|^APP_KEY=.*|APP_KEY=${APP_KEY}|" .env || true
else
    # Check if .env has a key, otherwise generate one
    CURRENT_KEY=$(grep -E "^APP_KEY=" .env | cut -d '=' -f2-)
    if [ -z "$CURRENT_KEY" ]; then
        echo "No APP_KEY found. Generating application key..."
        php artisan key:generate --force
    else
        echo "Found existing APP_KEY in .env."
    fi
fi

# 3. Create storage link
php artisan storage:link || true

# 4. Prepare SQLite fallback if used
mkdir -p database
if [ ! -f database/database.sqlite ]; then
    touch database/database.sqlite
fi

# 5. Run database migrations (non-blocking if DB is temporarily unreachable)
echo "Running database migrations..."
php artisan migrate --force || echo "Warning: Migrations failed or database not reachable yet. Continuing startup..."

# 6. Start the PHP server on the assigned PORT
PORT="${PORT:-10000}"
echo "=== Starting server on 0.0.0.0:${PORT} ==="
exec php artisan serve --host=0.0.0.0 --port="${PORT}"
