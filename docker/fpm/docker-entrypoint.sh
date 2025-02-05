#!/usr/bin/env sh
ME=$(basename $0)

write_php_fpm() {
  # write the php-fpm config
  # Here if the HOST path begins with unix: we treat it as unix
  # file socket binding therefore, we must trim unix: from the start of the string
  UNIX_SOCKET_START="unix:"
  LENGTH=${#UNIX_SOCKET_START}
  if [[ $PHP_FPM_HOST == unix:* ]]; then
    LISTEN=${PHP_FPM_HOST:LENGTH:${#PHP_FPM_HOST}}
  else
      LISTEN=${PHP_FPM_HOST}
  fi
  { \
      echo "[global]"; \
      echo "daemonize = no"; \
      echo "[www]"; \
      echo "listen = $LISTEN"; \
      echo "listen.owner = www-data"; \
      echo "listen.group = www-data"; \
      echo "ping.path = /ping"; \
      echo "pm = dynamic"; \
      echo "pm.status_path = /status"; \
      echo "pm.max_children = ${FPM_PM_MAX_CHILDREN:-5}"; \
      echo "pm.start_servers = ${FPM_PM_START_SERVERS:-2}"; \
      echo "pm.min_spare_servers = ${FPM_PM_MIN_SPARE_SERVERS:-1}"; \
      echo "pm.max_spare_servers = ${FPM_PM_MAX_SPARE_SERVERS:-3}"; \
      echo "pm.max_requests = ${FPM_PM_MAX_REQUESTS:-512}";
  } > /usr/local/etc/php-fpm.d/zz-docker.conf
}

write_env() {
  {
    echo "APP_NAME=\"${APP_NAME:-app}\""
    echo "APP_ENV=${APP_ENV:-local}"
    echo "APP_DEBUG=${APP_DEBUG:-true}"
    echo "APP_KEY=$APP_KEY"
    echo "APP_URL=\"${APP_URL:-http//:localhost}\""
    echo "DB_CONNECTION=${DB_CONNECTION-mysql}"
    echo "DB_HOST=${DB_HOST:-localhost}"
    echo "DB_PORT=${DB_PORT:-3306}"
    echo "DB_DATABASE=${DB_DATABASE:-db}"
    echo "DB_USERNAME=\"${DB_USERNAME:-db}\""
    echo "DB_PASSWORD=\"${DB_PASSWORD:-db}\""
    echo "CACHE_DRIVER=${CACHE_DRIVER:-file}"
    echo "QUEUE_CONNECTION=${QUEUE_CONNECTION:-sync}"
  } >$APP_DIR/.env
}

artisan_key_generate() {
  # Generate application keys
  # We first navigate to application directory to generate application key
  # Then we navigate back to the script exection context to avoid any issue with
  # the rest of script execution
  CWD=$(pwd)
  cd $APP_DIR &&
  $(which php) artisan key:generate --force  && # && $(which php) artisan config:clear \
  cd $CWD
}

start_cron_job() {
  echo 'Starting cron tasks...'
  # echo "* * * * * cd ${APP_DIR} && /usr/local/bin/php artisan schedule:run >> /dev/null 2>&1" >>/etc/crontabs/root
  crontab -l | { cat; echo "* * * * * cd ${APP_DIR} && /usr/local/bin/php artisan schedule:run >> /dev/null 2>&1"; } | crontab -
  crond -l 2 -f >/dev/stdout 2>/dev/stderr &
}

auto_envsubst() {
  local template_dir="${NGINX_ENVSUBST_TEMPLATE_DIR:-/etc/nginx/templates}"
  local suffix="${NGINX_ENVSUBST_TEMPLATE_SUFFIX:-.template}"
  local output_dir="${NGINX_ENVSUBST_OUTPUT_DIR:-/etc/nginx/conf.d}"

  local template defined_envs relative_path output_path subdir
  defined_envs=$(printf '${%s} ' $(env | cut -d= -f1))
  [ -d "$template_dir" ] || return 0
  if [ ! -w "$output_dir" ]; then
    echo "$ME: ERROR: $template_dir exists, but $output_dir is not writable"
    echo >&3 "$ME: ERROR: $template_dir exists, but $output_dir is not writable"
    return 0
  fi
  find "$template_dir" -follow -type f -name "*$suffix" -print | while read -r template; do
    relative_path="${template#$template_dir/}"
    output_path="$output_dir/${relative_path%$suffix}"
    subdir=$(dirname "$relative_path")
    # create a subdirectory where the template file exists
    mkdir -p "$output_dir/$subdir"
    echo >&3 "$ME: Running envsubst on $template to $output_path"
    envsubst "$defined_envs" < "$template" > "$output_path"
  done
}


echo "Rewriting storage permissions..."
chmod -R 775 ${APP_DIR}/storage

# Calling auto_envsubst to compile nginx templates
auto_envsubst

# Writing PHP FPM environement variables
echo "Writing PHP FPM environment variables..."
write_php_fpm

# Write environment files
echo "Writing application environment..."
write_env

# Generate application keys
echo "Generating application encryption keys..."
artisan_key_generate

# Start cron jobs
echo "Starting cron jobs..."
start_cron_job

if [ ! -z "$WWWUSER" ]; then
    addgroup -G $WWWUSER www-data
fi

if [ ! -d /.composer ]; then
    mkdir /.composer
fi

chmod -R ugo+rw /.composer

if [ $# -gt 0 ]; then
    exec gosu $WWWUSER "$@"
else
    exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
fi