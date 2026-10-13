#!/usr/bin/env bash
# Build locally and package the PHP application for cPanel.
set -euo pipefail

PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$PROJECT_ROOT"

for tool in php composer npm rsync zip; do
  command -v "$tool" >/dev/null 2>&1 || { echo "Missing required tool: $tool" >&2; exit 1; }
done

if [[ ! -d vendor || ! -d node_modules ]]; then
  echo "Install local dependencies first: composer install && npm ci" >&2
  exit 1
fi

npm run build

PACKAGE_TMP="$(mktemp -d "${TMPDIR:-/tmp}/sizdah-cpanel.XXXXXX")"
trap 'rm -rf "$PACKAGE_TMP"' EXIT
PACKAGE_ROOT="$PACKAGE_TMP/site"
mkdir -p "$PACKAGE_ROOT"

# Use an allowlist: local secrets, database contents, backups and logs never
# enter the application archive. Work from the current files, including edits.
rsync -a --relative \
  --exclude='/bootstrap/cache/***' \
  --exclude='/bootstrap/ssr/***' \
  --exclude='/database/*.sqlite*' \
  --exclude='*.sql' \
  --exclude='/public/hot' \
  --exclude='/public/storage' \
  app bootstrap config database lang public resources routes vendor \
  artisan composer.json composer.lock "$PACKAGE_ROOT/"

mkdir -p "$PACKAGE_ROOT/bootstrap/cache" \
  "$PACKAGE_ROOT/storage/app/private" \
  "$PACKAGE_ROOT/storage/app/public" \
  "$PACKAGE_ROOT/storage/framework/cache/data" \
  "$PACKAGE_ROOT/storage/framework/sessions" \
  "$PACKAGE_ROOT/storage/framework/views" \
  "$PACKAGE_ROOT/storage/logs"
rsync -a --safe-links storage/app/public/ "$PACKAGE_ROOT/storage/app/public/"
cp docs/CPANEL-DEPLOYMENT.md "$PACKAGE_ROOT/DEPLOYMENT.md"
cp .env.example "$PACKAGE_ROOT/.env.example"

cd "$PACKAGE_ROOT"
php <<'PHP'
<?php
$path = '.env.example';
$env = file_get_contents($path);
$env = str_replace(
    ['APP_ENV=local', 'APP_DEBUG=true', 'APP_URL=http://localhost:8000', 'QUEUE_CONNECTION=database', 'LOG_LEVEL=debug'],
    ['APP_ENV=production', 'APP_DEBUG=false', 'APP_URL=https://example.com', 'QUEUE_CONNECTION=sync', 'LOG_LEVEL=warning'],
    $env,
);
file_put_contents($path, $env);
PHP

# Only the staging copy loses dev dependencies. Never cache local .env values,
# absolute view paths or routes into the upload. These commands need no DB.
export CACHE_STORE=array SESSION_DRIVER=array QUEUE_CONNECTION=sync
composer install --no-dev --optimize-autoloader --no-interaction --no-progress --no-scripts
composer check-platform-reqs --no-dev
php artisan package:discover --ansi
php artisan filament:assets --ansi

# Package a relative link instead of the development machine's absolute link.
# Some cPanel extractors require recreating this link after extraction.
ln -s ../storage/app/public public/storage

OUTPUT_DIR="$PROJECT_ROOT/dist/cpanel"
mkdir -p "$OUTPUT_DIR"
zip -qry "$PACKAGE_TMP/sizdah-cpanel.zip" .
mv "$PACKAGE_TMP/sizdah-cpanel.zip" "$OUTPUT_DIR/sizdah-cpanel.zip"
echo "Upload archive: $OUTPUT_DIR/sizdah-cpanel.zip"
echo "Configure .env, import the database separately, and point the domain at public/."
