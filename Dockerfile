FROM php:8.2-fpm-alpine

LABEL MAINTAINER="Sidoine Azandrew <azandrewdevelopper@gmail.com>"

ARG WWWGROUP
# ARG GITHUB_ACCESS_TOKEN
ARG TEMPLATE_DIRECTORY=./docker
ARG PROJECT_CONTEXT=.
ARG PROXY_PORT=8080
ARG PROXY_HOST=app

ENV NGINX_USER=nginx
ENV PHP_USER=www-data
ENV PHP_USER_GROUP=www-data
ENV APP_DIR=/var/www/html
ENV BIN_DIR=${APP_DIR}/.bin
# ENV COMPOSER_GITHUB_ACCESS_TOKEN=${GITHUB_ACCESS_TOKEN}

# Nginx http server environment variables
ENV PROXY_HOST=app
ENV PROXY_PORT=${PROXY_PORT}

# PHP FPM CGI environment variable
ENV PHP_FPM_HOST=127.0.0.1:9000
ENV PHP_XDEBUG_REMOTE_PORT=9003

# Setting PHP environment variables
ENV PHP_POST_MAX_SIZE=80M
ENV PHP_UPLOAD_MAX_FILESIZE=100M
ENV PHP_MAX_EXECUTION_TIME=60
ENV PHP_MEMORY_LIMIT=1G

# Optional, force UTC as server time
RUN echo "UTC" > /etc/timezone
ENV TZ=UTC
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone
ENV APP_TIMEZONE=UTC

WORKDIR $APP_DIR

# Create .opcache directory
RUN mkdir ${APP_DIR}/.opcache

RUN apk add --no-cache --update zlib-dev \
    libmemcached-libs zlib libzip-dev bzip2-dev \
    make openssl-dev libcap supervisor procps

# Install gosu binary
ENV GOSU_VERSION 1.12
RUN set -eux; \
    \
    apk add --no-cache --virtual .gosu-deps \
    ca-certificates \
    dpkg \
    gnupg \
    ; \
    \
    dpkgArch="$(dpkg --print-architecture | awk -F- '{ print $NF }')"; \
    wget -O /usr/local/bin/gosu "https://github.com/tianon/gosu/releases/download/$GOSU_VERSION/gosu-$dpkgArch"; \
    wget -O /usr/local/bin/gosu.asc "https://github.com/tianon/gosu/releases/download/$GOSU_VERSION/gosu-$dpkgArch.asc"; \
    \
    # verify the signature
    export GNUPGHOME="$(mktemp -d)"; \
    gpg --batch --keyserver hkps://keys.openpgp.org --recv-keys B42F6819007F00F88E364FD4036A9C25BF357DD4; \
    gpg --batch --verify /usr/local/bin/gosu.asc /usr/local/bin/gosu; \
    command -v gpgconf && gpgconf --kill all || :; \
    rm -rf "$GNUPGHOME" /usr/local/bin/gosu.asc; \
    \
    # clean up fetch dependencies
    apk del --no-network .gosu-deps; \
    \
    chmod +x /usr/local/bin/gosu; \
    # verify that the binary works
    gosu --version; \
    gosu nobody true

# For standalone container we add nginx http server
RUN apk add --no-cache nginx \
    && rm -rf /tmp/*

# Install and enable igbinary & memcached extension
RUN set -xe && \
    cd /tmp/ && \
    apk add --no-cache --update --virtual .phpize-deps $PHPIZE_DEPS && \
    apk add --no-cache --update --virtual .memcached-deps zlib-dev libmemcached-dev pcre-dev cyrus-sasl-dev

# Install igbinary (memcached's deps)
RUN pecl install igbinary && \
    # Install memcached
    ( \
    pecl install --nobuild memcached && \
    cd "$(pecl config-get temp_dir)/memcached" && \
    phpize && \
    ./configure --enable-memcached-igbinary && \
    make -j$(nproc) && \
    make install && \
    cd /tmp/ \
    ) \
    && docker-php-ext-enable memcached igbinary

# Install intl extension
RUN apk add --no-cache \
    icu-dev \
    && docker-php-ext-install -j$(nproc) intl \
    && docker-php-ext-enable intl \
    && rm -rf /tmp/*

# Install mbstring extension
RUN apk add --no-cache \
    oniguruma-dev \
    && docker-php-ext-install mbstring \
    && docker-php-ext-enable mbstring \
    && rm -rf /tmp/*

# Setup bzip2 extension
RUN apk add --no-cache \
    bzip2-dev \
    && docker-php-ext-install -j$(nproc) bz2 \
    && docker-php-ext-enable bz2 \
    && rm -rf /tmp/*

# Setup GD extension
# Uncomment the code below to enable GD image library extension
# RUN apk add --no-cache \
#     freetype \
#     libjpeg-turbo \
#     libpng \
#     freetype-dev \
#     libjpeg-turbo-dev \
#     libpng-dev \
#     && docker-php-ext-configure gd \
#     --with-freetype=/usr/include/ \
#     --with-jpeg=/usr/include/ \
#     && docker-php-ext-install -j$(nproc) gd \
#     && docker-php-ext-enable gd \
#     && apk del --no-cache \
#     freetype-dev \
#     libjpeg-turbo-dev \
#     libpng-dev \
#     && rm -rf /tmp/*

# Install opcache, xdebug, redis
RUN apk add --update --no-cache linux-headers \
    && rm -rf /tmp/*

RUN docker-php-source extract \
    && pecl install opcache redis apcu \
    && pecl install xdebug \
    && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.log_level=0" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request=trigger" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=0.0.0.0" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_port=${PHP_XDEBUG_REMOTE_PORT}" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_handler=dbgp" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.discover_client_host=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && docker-php-ext-enable xdebug \
    && docker-php-ext-enable opcache redis apcu \
    && docker-php-source delete \
    && rm -rf /tmp/*

RUN pecl install pcov \
    && docker-php-ext-enable pcov \
    && rm -rf /tmp/*

# Install pecl install msgpack
RUN pecl install msgpack \
    && docker-php-ext-enable msgpack \
    && rm -rf /tmp/*

# Install ldap extension
RUN apk add ldb-dev libldap openldap-dev \
    && docker-php-ext-configure ldap \
    && docker-php-ext-install ldap \
    && docker-php-ext-enable  ldap

# Enable pdo extensions
RUN docker-php-ext-install zip exif pcntl pdo_mysql bcmath sockets \
    && docker-php-ext-enable pdo_mysql zip exif pcntl bcmath sockets \
    && rm -rf /tmp/*


# Install soap extension
# Uncomment code below to enable soap extension
# RUN apk add libxml2-dev \
#     && docker-php-ext-configure soap \
#     && docker-php-ext-install soap \
#     && docker-php-ext-enable  soap \
#     && rm -rf /tmp/*

# YAML extension
RUN apk add --no-cache yaml-dev

# Create a virtual reference for all those packages that can later be deleted
RUN apk add --no-cache --virtual .build-deps \
    g++ make autoconf

RUN pecl install yaml && \
    docker-php-ext-enable yaml

# Delete the build dependencies
RUN apk del --purge .build-deps

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Remove all cached files
RUN rm  -rf /tmp/* /var/cache/apk/* \
    && apk del .memcached-deps .phpize-deps

# Configure directory for supervisor
RUN mkdir -p /var/log/supervisor \
    && mkdir -p /var/log/supervisor/log \
    && touch /var/log/supervisor/log/supervisord.log \
    && chmod 775 /var/log/supervisor/log/supervisord.log

# We add permissions for nginx use when running nginx
# Bring in gettext so we can get `envsubst`, then throw
# the rest away. To do this, we need to install `gettext`
# then move `envsubst` out of the way so `gettext` can
# be deleted completely, then move `envsubst` back.
# This line was copied from nginx-alpine official image
RUN apk add --no-cache --virtual .gettext gettext \
    && mv /usr/bin/envsubst /tmp/ \
    \
    && runDeps="$( \
    scanelf --needed --nobanner /tmp/envsubst \
    | awk '{ gsub(/,/, "\nso:", $2); print "so:" $2 }' \
    | sort -u \
    | xargs -r apk info --installed \
    | sort -u \
    )" \
    && apk add --no-cache $runDeps \
    && apk del .gettext \
    && mv /tmp/envsubst /usr/local/bin/

# Configure permission for nginx user
RUN mkdir /var/cache/nginx

# Create nginx configuration directory
RUN mkdir -m 777 /etc/nginx/conf.d

COPY ${TEMPLATE_DIRECTORY}/fpm/nginx/templates /etc/nginx/templates
COPY ${TEMPLATE_DIRECTORY}/fpm/nginx/nginx.conf.prod /etc/nginx/nginx.conf

RUN chown -R $NGINX_USER:$NGINX_USER $APP_DIR && chmod -R 755 $APP_DIR && \
    chown -R $NGINX_USER:$NGINX_USER /var/cache/nginx && \
    chown -R $NGINX_USER:$NGINX_USER /var/log/supervisor && \
    chown -R $NGINX_USER:$NGINX_USER /etc/nginx/conf.d

RUN touch /var/run/nginx.pid && \
    chown -R $NGINX_USER:$NGINX_USER /var/run/nginx.pid

# Copy application files to container
COPY --chown=$PHP_USER:$PHP_USER_GROUP ${PROJECT_CONTEXT} ${APP_DIR}
COPY --chown=$PHP_USER:$PHP_USER_GROUP ${TEMPLATE_DIRECTORY}/.env.example ${APP_DIR}/.env

# Cleanup
COPY ${TEMPLATE_DIRECTORY}/cleanup.sh /cleanup.sh
RUN chmod +x /cleanup.sh \
    && /cleanup.sh \
    && rm -f /cleanup.sh

# Make the storage directory writable
RUN chmod -R 777 ${APP_DIR}/storage/

# For risk check make sure wwww-data:www-data own the application directory
RUN chown -R $PHP_USER:$PHP_USER_GROUP ${APP_DIR}

# Switch to composer user to install package using a non root user
USER $PHP_USER

# Install composer
RUN mkdir -p $BIN_DIR && chown -R $PHP_USER:$PHP_USER ${BIN_DIR}
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=$BIN_DIR --filename=composer

# Step below is only required if using private packages from google
# Add github keys to composer in order to install packages from github
# RUN $BIN_DIR/composer config --global github-oauth.github.com ${COMPOSER_GITHUB_ACCESS_TOKEN}

# Remove the vendor directory and reinstall composer packages
RUN rm -rf ${APP_DIR}/vendor

# Install php packages
RUN $BIN_DIR/composer update --no-ansi --no-dev --no-interaction --no-progress --optimize-autoloader

USER root

# Entrypoint
COPY ${TEMPLATE_DIRECTORY}/fpm/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
COPY ${TEMPLATE_DIRECTORY}/fpm/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY ${TEMPLATE_DIRECTORY}/php.ini /usr/local/etc/php/conf.d/99-local.ini
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE ${PROXY_PORT} 443

ENTRYPOINT ["docker-entrypoint.sh"]
