FROM php:8.3-fpm

# Instala dependências do sistema
RUN apt-get update \
    && apt-get install -y \
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
        ffmpeg \
        gnupg --no-install-recommends

# Instala Node.js 20.x
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Configura extensões PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-configure intl \
    && pecl install imagick \
    && docker-php-ext-enable imagick \
    && docker-php-ext-install pdo pdo_mysql gd zip bcmath opcache intl exif pcntl

# Instala o Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Cria diretórios necessários
RUN mkdir -p /var/www/html
WORKDIR /var/www/html

# Copia arquivos do projeto
COPY . /var/www/html

# Copia configuração customizada do PHP
COPY php.ini /usr/local/etc/php/conf.d/custom.ini

# Instala dependências do PHP (sem scripts para evitar acesso ao banco durante build)
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-scripts

# Instala dependências do Node.js e compila assets
RUN npm install && npm run build

# Permissões para storage e cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Configuração do PHP-FPM
RUN echo "listen = 9000" >> /usr/local/etc/php-fpm.d/www.conf

# Expõe a porta do PHP-FPM
EXPOSE 9000

# Mantém o container rodando com PHP-FPM
