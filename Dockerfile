FROM dunglas/frankenphp:1.5-php8.3

# Arguments
ARG user=laravel
ARG uid=1000

# Install system dependencies & PHP extensions
RUN apt-get update && apt-get install -y --no-install-recommends unzip && rm -rf /var/lib/apt/lists/*

RUN install-php-extensions \
    pdo_pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    redis \
    intl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user
RUN useradd -G www-data,root -u $uid -d /home/$user $user \
    && mkdir -p /home/$user/.composer \
    && chown -R $user:$user /home/$user \
    && chown -R $user:$user /data/caddy && chown -R $user:$user /config/caddy

WORKDIR /var/www

USER $user
