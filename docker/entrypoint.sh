#!/usr/bin/env sh
set -eu

cd /var/www/html

if [ ! -f .env ] && [ -f .env.example ]; then
    cp .env.example .env
fi

if [ -f .env ] && ! grep -q "^APP_KEY=base64:" .env; then
    php artisan key:generate --force --no-interaction
fi

php artisan storage:link --no-interaction || true

if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
    php artisan migrate --force --no-interaction
fi

exec "$@"
