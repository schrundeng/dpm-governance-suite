FROM php:8.2-apache

# Install unzip dan dependency sistem
RUN apt-get update && apt-get install -y unzip && rm -rf /var/lib/apt/lists/*

# Aktifkan mod_rewrite
RUN a2enmod rewrite

# Install ekstensi PHP
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Izinkan .htaccess override
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Set permission uploads
RUN mkdir -p /var/www/html/uploads && \
    chown -R www-data:www-data /var/www/html/uploads && \
    chmod 755 /var/www/html/uploads

COPY ./html /var/www/html