#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$ROOT_DIR"

if [[ -d .git && "${SKIP_GIT_PULL:-0}" != "1" ]]; then
  echo "==> Pulling latest code"
  git pull --ff-only
fi

export RUN_MIGRATIONS="${RUN_MIGRATIONS:-true}"
export RUN_SEEDERS="${RUN_SEEDERS:-false}"

echo "==> Building and starting containers"
docker-compose up -d --build

echo "==> Optimizing caches"
docker-compose exec -T app php artisan config:cache --no-interaction || true
docker-compose exec -T app php artisan route:cache --no-interaction || true
docker-compose exec -T app php artisan view:cache --no-interaction || true

echo "==> Done"
