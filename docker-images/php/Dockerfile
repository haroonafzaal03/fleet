FROM php:7.4-fpm
MAINTAINER Visualr  <info@visualr.com.au>

# PHP config
RUN apt-get update \
    && apt-get install -y \
        apt-utils \
#        curl \
        dnsutils \
        git \
        unzip \
        wget \
        zip \
        nano \
        libcurl4-gnutls-dev \
        libmcrypt-dev \
        libtidy-dev \
        libbz2-dev \
        libxml2-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libjpeg62 \
        libpng-dev \
        libssl-dev \
        libicu-dev \
        libc-client-dev \
        libkrb5-dev \
        jpegoptim \
        libzip-dev \
        gnupg2 \
        libzmq3-dev \
    && pecl install mcrypt-1.0.4 \
    && docker-php-ext-enable mcrypt \
    && docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg \
    && docker-php-ext-configure imap --with-kerberos --with-imap-ssl \
    && docker-php-ext-install \
        bcmath \
        bz2 \
        calendar \
#        curl \
#        ctype \
#        dom \
        exif \
#        fileinfo \
#        ftp \
        gd \
        gettext \
#        hash \
        imap \
        intl \
#        json \
#        mbstring \
        mysqli \
        opcache \
#        pdo \
        pdo_mysql \
#        session \
#        simplexml \
        shmop \
        soap \
        sockets \
        sysvmsg \
        sysvsem \
        sysvshm \
        tidy \
#        tokenizer \
#        wddx \
#        xml \
        zip

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
        php composer-setup.php && \
        php -r "unlink('composer-setup.php');" && \
        mv composer.phar /usr/bin/composer


WORKDIR /var/www/

CMD php-fpm