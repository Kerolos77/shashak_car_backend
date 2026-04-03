#!/bin/bash
# ============================================================
#  SHAKSHAK — Server Commands Reference
#  Save this file and use it on every deployment
# ============================================================

# ─────────────────────────────────────────────────────────
# 0. INITIAL SETUP (first time only)
# ─────────────────────────────────────────────────────────
cp .env.example .env
composer install --no-dev --optimize-autoloader
php artisan key:generate

# ─────────────────────────────────────────────────────────
# 1. DEPLOY (run every time you push new code)
# ─────────────────────────────────────────────────────────

# 1.1 Pull latest code (if using git on server)
# git pull origin main

# 1.2 Install/update PHP dependencies
composer install --no-dev --optimize-autoloader

# 1.3 Run all pending migrations
php artisan migrate --force

# 1.4 Clear & rebuild all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear

# 1.5 Rebuild optimized caches (production)
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 1.6 Set correct storage permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# 1.7 Symlink storage (first time or after fresh setup)
php artisan storage:link

# ─────────────────────────────────────────────────────────
# 2. QUEUE WORKER (for background jobs / notifications)
#    Run as a background service (supervisor recommended)
# ─────────────────────────────────────────────────────────

# Start queue worker
php artisan queue:work --sleep=3 --tries=3 --max-time=3600

# Restart queue workers after deployment (safe)
php artisan queue:restart

# ─────────────────────────────────────────────────────────
# 3. SOKETI (Real-Time WebSocket Server)
#    Required for Pusher-compatible real-time events
# ─────────────────────────────────────────────────────────

# Install soketi globally (first time only)
npm install -g @soketi/soketi

# Start soketi server (runs on port 6001 by default)
soketi start

# Or using the config file:
soketi start --config=soketi.json

# ─────────────────────────────────────────────────────────
# 4. LOCAL DEVELOPMENT (Windows)
# ─────────────────────────────────────────────────────────

# Start Laravel dev server
php artisan serve --port=8000

# Start Soketi locally (in a separate terminal)
soketi start

# Run migrations
php artisan migrate

# Rollback last migration (if needed)
php artisan migrate:rollback

# Reset ALL migrations and re-run (DANGER: deletes all data)
# php artisan migrate:fresh --seed

# ─────────────────────────────────────────────────────────
# 5. USEFUL DEBUG COMMANDS
# ─────────────────────────────────────────────────────────

# Check all registered routes
php artisan route:list

# Check migration status
php artisan migrate:status

# Tail the application log
tail -f storage/logs/laravel.log          # Linux/Mac
# Get-Content storage/logs/laravel.log -Wait  # Windows PowerShell

# Test Paymob webhook locally (use ngrok to expose port 8000)
# ngrok http 8000
# Then set PAYMOB_WEBHOOK_URL=https://your-ngrok-url.ngrok.io/api/v1/paymob/webhook

# Clear failed jobs
php artisan queue:flush

# ─────────────────────────────────────────────────────────
# 6. MIGRATIONS ADDED IN LATEST SESSION
# ─────────────────────────────────────────────────────────
# These will run automatically with: php artisan migrate --force
#
# ✅ 2026_03_03_220822_update_order_offers_status_enum
#    → Expanded offer status to 6 values:
#      pending, countered, user_accepted, driver_accepted, user_denied, driver_canceled
#
# ✅ 2026_03_04_014853_add_payment_status_to_orders_table
#    → Added payment_status (pending/paid/failed) and paymob_order_id to orders

# ─────────────────────────────────────────────────────────
# 7. SUPERVISOR CONFIG (keep queue worker alive on Linux)
# ─────────────────────────────────────────────────────────
# /etc/supervisor/conf.d/shakshak-worker.conf
#
# [program:shakshak-queue]
# process_name=%(program_name)s_%(process_num)02d
# command=php /var/www/shakshak/artisan queue:work --sleep=3 --tries=3 --max-time=3600
# autostart=true
# autorestart=true
# stopasgroup=true
# killasgroup=true
# user=www-data
# numprocs=2
# redirect_stderr=true
# stdout_logfile=/var/www/shakshak/storage/logs/queue.log
# stopwaitsecs=3600
#
# After editing supervisor config:
# supervisorctl reread
# supervisorctl update
# supervisorctl start shakshak-queue:*
