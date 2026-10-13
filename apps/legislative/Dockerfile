FROM php:8.2-apache

# Install unzip dan dependency sistem
RUN apt-get update && apt-get install -y unzip && rm -rf /var/lib/apt/lists/*

# Enable Apache rewrite module
RUN a2enmod rewrite

# Install MySQL extension
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Izinkan .htaccess override
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Set working directory
WORKDIR /var/www/html

# Set permission uploads
RUN mkdir -p /var/www/html/uploads && \
    chown -R www-data:www-data /var/www/html/uploads && \
    chmod 775 /var/www/html/uploads

# Copy project files
COPY ./html /var/www/html

# Set permissions
RUN chown -R www-data:www-data /var/www/html

# Enable error logging and increase upload limits
RUN echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/error.ini && \
    echo "display_errors = On" >> /usr/local/etc/php/conf.d/error.ini && \
    echo "upload_max_filesize = 25M" >> /usr/local/etc/php/conf.d/uploads.ini && \
    echo "post_max_size = 25M" >> /usr/local/etc/php/conf.d/uploads.ini

EXPOSE 80

CMD ["apache2-foreground"]
