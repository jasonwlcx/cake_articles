#start with our base image (the foundation) - version 7.1.5
FROM php:7.1.5-apache

#install all the system dependencies and enable PHP modules 
RUN apt-get update && apt-get install -y \
      libicu-dev \
      libpq-dev \
      libmcrypt-dev \
      libxml2-dev \
      mysql-client \
      git \
      zip \
      unzip \
    && rm -r /var/lib/apt/lists/* \
    && docker-php-ext-configure pdo_mysql --with-pdo-mysql=mysqlnd \
    && docker-php-ext-install \
      intl \
      mbstring \
      mcrypt \
      mysqli \
      pcntl \
      pdo_mysql \
      pdo_pgsql \
      pgsql \
      simplexml \
      zip \
      opcache

#install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

#set our application folder as an environment variable
ENV APP_HOME /var/www/html

#change uid and gid of apache to docker user uid/gid
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

#change the web_root to laravel /var/www/html/public folder
RUN sed -i -e "s/html/html\/webroot/g" /etc/apache2/sites-enabled/000-default.conf

#copy source files and run composer
COPY . $APP_HOME

# install all PHP dependencies
RUN composer install --no-interaction

#change ownership of our applications
RUN chown -R www-data:www-data $APP_HOME

# Add cake and composer command to system path
ENV PATH="${PATH}:/var/www/html/lib/Cake/Console"
ENV PATH="${PATH}:/var/www/html/app/Vendor/bin"

# Create tmp directory and make it writable by the web server
RUN mkdir -p \
    tmp/cache/models \
    tmp/cache/persistent \
  && chown -R :www-data \
    tmp \
  && chmod -R 770 \
    tmp

# Enable Apache modules and restart
RUN a2enmod rewrite \
  && service apache2 start

EXPOSE 80
