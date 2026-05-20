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

if docker compose version >/dev/null 2>&1; then
  COMPOSE_CMD="docker compose"
else
  COMPOSE_CMD="docker-compose"
fi

echo "==> Building and starting containers"
# Legacy docker-compose sometimes fails to recreate containers with newer images.
# Recreate only the app container (keeps DB volume intact).
if [[ "${FORCE_RECREATE_APP:-1}" == "1" ]]; then
  $COMPOSE_CMD rm -sf app || true
fi

$COMPOSE_CMD up -d --build

echo "==> Optimizing caches"
$COMPOSE_CMD exec -T app php artisan config:cache --no-interaction || true
$COMPOSE_CMD exec -T app php artisan route:cache --no-interaction || true
$COMPOSE_CMD exec -T app php artisan view:cache --no-interaction || true

echo "==> Done"
