FROM php:8.1-cli

# Устанавливаем зависимости для сборки PECL-расширений
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
       build-essential \
       libpcre3-dev \
       libtool \
    && pecl install sync-1.0.0 \
    && docker-php-ext-enable sync \
    && docker-php-ext-install shmop \
    && rm -rf /var/lib/apt/lists/*

# Копируем конфиги и скрипты
WORKDIR /app
COPY php.ini /usr/local/etc/php/php.ini
COPY writer.php reader.php /app/
COPY Makefile /app/

CMD ["make", "run"]
