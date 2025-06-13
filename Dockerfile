FROM php:8.2-cli

# Устанавливаем зависимости для сборки PECL-расширений
RUN apt-get update \
    && apt-get install -y --no-install-recommends

RUN pecl install sync
RUN docker-php-ext-enable sync
RUN docker-php-ext-install shmop
RUN rm -rf /var/lib/apt/lists/*

# Копируем конфиги и скрипты
WORKDIR /app
COPY php.ini /usr/local/etc/php/php.ini
COPY writer.php reader.php /app/
COPY Makefile /app/

CMD ["make", "run"]
