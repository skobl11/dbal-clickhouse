FROM debian:bullseye-slim

ENV LANG C.UTF-8
ENV DEBIAN_FRONTEND noninteractive

# update & install main binaries
RUN apt-get -y update
RUN apt-get -y install unzip vim supervisor nano wget gnupg2

# add repos
RUN echo "deb https://packages.sury.org/php/ bullseye main" | tee /etc/apt/sources.list.d/php.list && \
    wget https://packages.sury.org/php/apt.gpg && apt-key add apt.gpg
RUN echo 'deb [trusted=yes] https://repo.symfony.com/apt/ /' | tee /etc/apt/sources.list.d/symfony-cli.list
RUN apt-get -y update

# install php 8
RUN apt-get -y install php8.1-xml php8.1-fpm php8.1-curl php8.1-mbstring php8.1-pgsql php8.1-zip

# configure symfony cli
RUN apt-get -y install symfony-cli

# expose nginx port
EXPOSE 81

# configure nginx
COPY docker_assets/nginx.conf /etc/nginx/sites-enabled/default

# configure application
COPY ./ /var/www/html
RUN chown www-data:www-data -R /var/www/html

# composer update
COPY docker_assets/composer.phar /var/www/html/composer.phar
RUN cd /var/www/html/ && php composer.phar update -vvv

# configure PHP-FPM
RUN mkdir --mode=07500 /run/php && \
        chown www-data:www-data /run/php

# add postgres user
RUN useradd -m postgres

# Configure supervisord
COPY docker_assets/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# start nginx + php-fpm
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

