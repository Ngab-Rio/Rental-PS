FROM php:8.2-cli

RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN docker-php-ext-enable mysqli

COPY . /app
WORKDIR /app

RUN chmod 600 database/*
RUN chmod 600 config/*
RUN chmod 600 js/*
RUN chmod 600 sidebar/*

CMD ["php", "-S", "localhost", "6543"]