#!/usr/bin/env /bin/sh

cleanup_app_directory() {
    if [ -d "$APP_DIR/docker" ]; then
        rm -rf "$APP_DIR/docker" "$APP_DIR/.Dockerfile" "$APP_DIR/.dockerignore" "$APP_DIR/.vite.config.js"
    fi
}
cleanup_app_directory
