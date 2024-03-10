FROM php:8.3.3-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    vim \
    unzip
RUN apt-get update && apt-get install -y libmariadb-dev    
RUN docker-php-ext-install mysqli  

WORKDIR /var/www
