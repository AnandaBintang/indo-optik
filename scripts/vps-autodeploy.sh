#!/usr/bin/env bash
set -euo pipefail

# VPS auto-deploy helper for IndoOptik.
# - Pull latest code from GitHub
# - Rebuild and restart Docker services
# - Run database migration safely

APP_DIR="${APP_DIR:-/root/indo-optik}"
BRANCH="${BRANCH:-main}"
REMOTE="${REMOTE:-origin}"
COMPOSE_FILE="${COMPOSE_FILE:-docker-compose.yml}"
APP_SERVICE="${APP_SERVICE:-app}"
HEALTH_TIMEOUT_SECONDS="${HEALTH_TIMEOUT_SECONDS:-120}"
LOCK_FILE="/tmp/indooptik-autodeploy.lock"

timestamp() { date +"%Y-%m-%d %H:%M:%S"; }
log() { echo "[$(timestamp)] $*"; }

if [ -f "$LOCK_FILE" ]; then
  log "Another deployment is running (lock: $LOCK_FILE). Exit."
  exit 1
fi
trap 'rm -f "$LOCK_FILE"' EXIT
touch "$LOCK_FILE"

if ! command -v docker >/dev/null 2>&1; then
  log "Docker is not installed."
  exit 1
fi

if docker compose version >/dev/null 2>&1; then
  COMPOSE_BIN=(docker compose)
elif command -v docker-compose >/dev/null 2>&1; then
  COMPOSE_BIN=(docker-compose)
else
  log "Docker Compose is not installed (need docker compose or docker-compose)."
  exit 1
fi

if ! command -v git >/dev/null 2>&1; then
  log "Git is not installed."
  exit 1
fi

if [ ! -d "$APP_DIR/.git" ]; then
  log "Invalid APP_DIR ($APP_DIR). Git repository not found."
  exit 1
fi

log "Starting auto-deploy in $APP_DIR"
cd "$APP_DIR"

if ! git diff --quiet || ! git diff --cached --quiet; then
  log "Working tree is dirty. Commit/revert local changes before deploy."
  exit 1
fi

log "Fetching latest from $REMOTE/$BRANCH"
git fetch "$REMOTE" "$BRANCH"
git checkout "$BRANCH"
git pull --ff-only "$REMOTE" "$BRANCH"

log "Building and restarting containers"
"${COMPOSE_BIN[@]}" -f "$COMPOSE_FILE" up -d --build

log "Waiting for app container to be running"
START_TIME="$(date +%s)"
while true; do
  STATUS="$("${COMPOSE_BIN[@]}" -f "$COMPOSE_FILE" ps --status running --services | grep -E "^${APP_SERVICE}$" || true)"
  if [ "$STATUS" = "$APP_SERVICE" ]; then
    break
  fi

  NOW="$(date +%s)"
  ELAPSED=$((NOW - START_TIME))
  if [ "$ELAPSED" -ge "$HEALTH_TIMEOUT_SECONDS" ]; then
    log "App service failed to reach running state in ${HEALTH_TIMEOUT_SECONDS}s."
    "${COMPOSE_BIN[@]}" -f "$COMPOSE_FILE" ps
    exit 1
  fi
  sleep 3
done

log "Running Laravel migration"
"${COMPOSE_BIN[@]}" -f "$COMPOSE_FILE" exec -T "$APP_SERVICE" php artisan migrate --force --no-interaction
"${COMPOSE_BIN[@]}" -f "$COMPOSE_FILE" exec -T "$APP_SERVICE" php artisan storage:link --no-interaction || true

log "Clearing and warming Laravel caches"
"${COMPOSE_BIN[@]}" -f "$COMPOSE_FILE" exec -T "$APP_SERVICE" php artisan optimize:clear --no-interaction
"${COMPOSE_BIN[@]}" -f "$COMPOSE_FILE" exec -T "$APP_SERVICE" php artisan config:cache --no-interaction
"${COMPOSE_BIN[@]}" -f "$COMPOSE_FILE" exec -T "$APP_SERVICE" php artisan route:cache --no-interaction
"${COMPOSE_BIN[@]}" -f "$COMPOSE_FILE" exec -T "$APP_SERVICE" php artisan view:cache --no-interaction

log "Deployment completed successfully."
