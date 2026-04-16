#!/bin/bash
set -e

echo "==> FaithStack boot..."

# Finish package discovery (skipped during build — needs .env)
echo "==> Discovering packages..."
php artisan package:discover --ansi || true

# Generate app key if missing
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "" ]; then
    echo "==> Generating APP_KEY..."
    php artisan key:generate --force
fi

# Wait for MySQL (extra safety beyond healthcheck)
echo "==> Waiting for MySQL..."
until php artisan db:monitor --max=1 2>/dev/null; do
    sleep 2
done

# Run migrations
echo "==> Running migrations..."
php artisan migrate --force

# Seed only on fresh DB (themes table empty = first boot)
ROW_COUNT=$(php artisan tinker --execute="echo \App\Models\Theme::count();" 2>/dev/null | tr -d '\n' || echo "0")
if [ "$ROW_COUNT" = "0" ]; then
    echo "==> Seeding themes..."
    php artisan db:seed --force
fi

# Clear caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Fix permissions (in case host mounts changed ownership)
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

echo "==> Starting PHP-FPM..."
exec "$@"
