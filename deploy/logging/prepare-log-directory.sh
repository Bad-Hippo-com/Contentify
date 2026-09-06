#!/usr/bin/env sh
set -eu

LOG_ROOT="${CONTENTIFY_LOG_ROOT:-/var/log/contentify}"
APP_USER="${CONTENTIFY_APP_USER:-www-data}"
APP_GROUP="${CONTENTIFY_APP_GROUP:-www-data}"

case "$LOG_ROOT" in
    /var/log/*) ;;
    *)
        echo "Refusing log directory outside /var/log: $LOG_ROOT" >&2
        exit 1
        ;;
esac

install -d -m 0750 -o "$APP_USER" -g "$APP_GROUP" "$LOG_ROOT"
touch "$LOG_ROOT/php-error.log" \
      "$LOG_ROOT/web-error.log" \
      "$LOG_ROOT/web-access.log" \
      "$LOG_ROOT/queue.log" \
      "$LOG_ROOT/scheduler.log" \
      "$LOG_ROOT/deployment.log"
chown "$APP_USER:$APP_GROUP" "$LOG_ROOT"/*.log
chmod 0640 "$LOG_ROOT"/*.log

echo "Prepared $LOG_ROOT for $APP_USER:$APP_GROUP"
