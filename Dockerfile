FROM php:8.1-fpm

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Instalar extensões do PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Instalar o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Definir o diretório de trabalho
WORKDIR /var/www

# Copiar o projeto Laravel
COPY . /var/www

# Ajustar permissões
RUN chown -R www-data:www-data /var/www

# Expor a porta da aplicação
EXPOSE 8000

# Comando para iniciar o servidor PHP embutido
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
