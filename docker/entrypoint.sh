#!/bin/sh
set -e

UPLOADS_DIR="/var/www/html/public/uploads"
VAR_DIR="/var/www/html/var"

mkdir -p "$UPLOADS_DIR/projects" "$UPLOADS_DIR/resume" "$UPLOADS_DIR/profile" "$VAR_DIR"

if chown -R www-data:www-data "$UPLOADS_DIR" "$VAR_DIR" 2>/dev/null; then
  chmod -R 775 "$UPLOADS_DIR" "$VAR_DIR" || true
else
  chmod -R 777 "$UPLOADS_DIR" "$VAR_DIR" || true
fi

exec apache2-foreground
