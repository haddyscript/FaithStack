#!/bin/bash
set -e

echo "==> FaithStack boot..."

# Clear stale host-generated bootstrap cache before anything else.
echo "==> Clearing bootstrap cache..."
rm -f /var/www/bootstrap/cache/packages.php
rm -f /var/www/bootstrap/cache/services.php
rm -f /var/www/bootstrap/cache/config.php
rm -f /var/www/bootstrap/cache/routes-v7.php
rm -f /var/www/bootstrap/cache/events.php

# Generate app key if missing
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "" ]; then
    echo "==> Generating APP_KEY..."
    php artisan key:generate --force
fi

# Rebuild package manifest without dev-only providers
echo "==> Discovering packages..."
php artisan package:discover --ansi

# Wait for MySQL (healthcheck in compose is the primary guard,
# this is a belt-and-suspenders check)
echo "==> Waiting for MySQL..."
until php artisan db:monitor --max=1 2>/dev/null; do
    sleep 2
done

# Run migrations — don't let a failed migration crash the container
echo "==> Running migrations..."
php artisan migrate --force || {
    echo "!!! Migration failed — check logs above. PHP-FPM will still start."
}

# Seed only on fresh DB (themes table empty = first boot)
ROW_COUNT=$(php artisan tinker --execute="echo \App\Models\Theme::count();" 2>/dev/null | tr -d '\n' || echo "0")
if [ "$ROW_COUNT" = "0" ]; then
    echo "==> Seeding themes..."
    php artisan db:seed --force || echo "!!! Seed failed."
fi

# Build caches (non-fatal — app still works without them in local dev)
echo "==> Caching config/routes/views..."
php artisan config:cache  || true
php artisan route:cache   || true
php artisan view:cache    || true

# Fix permissions
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

echo "==> Starting PHP-FPM..."
exec "$@"
