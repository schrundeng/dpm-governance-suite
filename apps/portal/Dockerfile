FROM php:8.2-apache

# Install unzip and system dependencies
RUN apt-get update && apt-get install -y unzip && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install PHP PDO MySQL extension
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Allow .htaccess overrides
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Set permissions for /var/www/html
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html
