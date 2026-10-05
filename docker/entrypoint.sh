#!/bin/sh
set -e

echo "=== Starting TalentFlow Container ==="

# 1. Ensure .env file exists
if [ ! -f .env ]; then
    echo "Creating .env from .env.example..."
    cp .env.example .env
fi

# 2. Sync all Render environment variables into .env so php artisan serve child process has them
echo "Syncing environment variables into .env..."
php -r '
$env = [];
if (file_exists(".env")) {
    $lines = file(".env", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $trimmed = trim($line);
        if (str_starts_with($trimmed, "#") || !str_contains($line, "=")) continue;
        [$k, $v] = explode("=", $line, 2);
        $env[trim($k)] = trim($v);
    }
}

// Ingest environment variables passed by Render
foreach (array_merge($_ENV, $_SERVER) as $k => $v) {
    if (is_string($v) && (
        str_starts_with($k, "APP_") ||
        str_starts_with($k, "DB_") ||
        str_starts_with($k, "DATABASE_") ||
        str_starts_with($k, "SESSION_") ||
        str_starts_with($k, "CACHE_") ||
        str_starts_with($k, "QUEUE_") ||
        str_starts_with($k, "MAIL_") ||
        str_starts_with($k, "LOG_")
    )) {
        $env[$k] = $v;
    }
}

$output = "";
foreach ($env as $k => $v) {
    $output .= "{$k}={$v}\n";
}
file_put_contents(".env", $output);
'

# 3. Ensure APP_KEY exists in .env
CURRENT_KEY=$(grep -E "^APP_KEY=" .env | cut -d '=' -f2-)
if [ -z "$CURRENT_KEY" ]; then
    echo "Generating missing APP_KEY..."
    php artisan key:generate --force
else
    echo "APP_KEY is present."
fi

# 4. Clear any stale caches
php artisan config:clear || true
php artisan cache:clear || true
php artisan view:clear || true

# 5. Create storage link
php artisan storage:link || true

# 6. Prepare SQLite fallback if used
mkdir -p database
if [ ! -f database/database.sqlite ]; then
    touch database/database.sqlite
fi
chmod -R 777 database storage bootstrap/cache

# 7. Run database migrations & seed demo accounts
echo "Running database migrations..."
php artisan migrate --force || echo "Warning: Migrations encountered an issue. Continuing..."
echo "Seeding initial roles, demo accounts, and jobs..."
php artisan db:seed --force || echo "Notice: Seeding completed or skipped."

# 8. Start the server on Render PORT
PORT="${PORT:-10000}"
echo "=== Starting server on 0.0.0.0:${PORT} ==="
exec php artisan serve --host=0.0.0.0 --port="${PORT}"
