FROM php:8.2-fpm

# Defina seu nome de usuário, ex: user=miqueias
ARG user=mfernando
ARG uid=1000

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    nano \
    wkhtmltopdf \
    tzdata

# Configurar fuso horário para São Paulo
ENV TZ=America/Sao_Paulo
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Instalar Node.js e npm
RUN curl -fsSL https://deb.nodesource.com/setup_14.x | bash -
RUN apt-get install -y nodejs npm

# Instalar o Supervisor
RUN apt-get update && apt-get install -y supervisor

# Criar o diretório para o socket do Supervisor
RUN mkdir -p /var/run/supervisor && chown -R root:root /var/run/supervisor

COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/supervisor/supervisord.conf /etc/supervisord.conf

# Instalar extensão Redis para PHP
RUN pecl install redis && docker-php-ext-enable redis

# Limpar cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensões PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd sockets zip

# Obter o Composer mais recente
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Criar usuário do sistema para executar comandos do Composer e Artisan
RUN useradd -G www-data,root -u $uid -d /home/$user $user

RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

RUN apt-get update && apt-get install -y cron

# Adicionar tarefa cron com binario do php
RUN echo "* * * * * root cd /var/www/ && /usr/local/bin/php artisan schedule:run >> /var/log/laravel_schedule.log 2>&1" > /etc/cron.d/laravel-scheduler \
    && crontab /etc/cron.d/laravel-scheduler


# Definir diretório de trabalho
WORKDIR /var/www

# Copiar configurações personalizadas do PHP
COPY docker/php/custom.ini /usr/local/etc/php/conf.d/custom.ini

# Expor as portas necessárias (se necessário)
EXPOSE 9000

# Executar o Supervisor como ponto de entrada
CMD cron && /usr/bin/supervisord -c /etc/supervisor/supervisord.conf
