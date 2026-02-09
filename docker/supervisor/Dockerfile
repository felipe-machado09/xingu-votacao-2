FROM php:8.3-fpm

# Instala dependências do sistema e supervisor
RUN apt-get update \
    && apt-get install -y \
        supervisor \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libzip-dev \
        zip \
        unzip \
        git \
        curl \
        libonig-dev \
        libxml2-dev \
        libpq-dev \
        libssl-dev \
        libmagickwand-dev \
        libicu-dev \
        ffmpeg --no-install-recommends \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-configure intl \
    && pecl install imagick \
    && docker-php-ext-enable imagick \
    && docker-php-ext-install pdo pdo_mysql gd zip bcmath opcache intl exif pcntl

# Instala o Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Cria diretórios necessários
RUN mkdir -p /var/www/html \
    && mkdir -p /var/log/supervisor \
    && mkdir -p /var/run

WORKDIR /var/www/html

# Copia arquivos do projeto
COPY . /var/www/html

# Copia configuração customizada do PHP
COPY php.ini /usr/local/etc/php/conf.d/custom.ini

# Instala dependências do PHP
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Permissões para storage e cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Cria diretório de configuração do supervisor
RUN mkdir -p /etc/supervisor/conf.d

# Copia configuração principal do supervisor
COPY docker/supervisor/supervisord.conf /etc/supervisor/supervisord.conf

# Copia configurações dos programas
COPY docker/supervisor/laravel-worker.conf /etc/supervisor/conf.d/laravel-worker.conf
COPY docker/supervisor/laravel-scheduler.conf /etc/supervisor/conf.d/laravel-scheduler.conf

# Expõe porta do supervisor (opcional, para monitoramento)
EXPOSE 9001

# Comando para iniciar o supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf", "-n"]
