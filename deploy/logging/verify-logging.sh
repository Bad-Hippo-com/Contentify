#!/usr/bin/env sh
set -eu

LOG_ROOT="${CONTENTIFY_LOG_ROOT:-/var/log/contentify}"
APP_ROOT="${CONTENTIFY_ROOT:-/var/www/contentify}"

test -d "$LOG_ROOT"
test -w "$LOG_ROOT"

cd "$APP_ROOT"
php artisan tinker --execute="Log::warning('contentify logging verification', ['environment' => app()->environment(), 'host' => gethostname()]);"

latest_log="$(ls -1t "$LOG_ROOT"/application-*.log 2>/dev/null | head -n 1)"
test -n "$latest_log"
grep -q 'contentify logging verification' "$latest_log"

echo "Laravel logging verified in $latest_log"
