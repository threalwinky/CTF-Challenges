FROM ubuntu:22.04@sha256:2b7412e6465c3c7fc5bb21d3e6f1917c167358449fecac8176c6e496e5c1f05f

ENV DEBIAN_FRONTEND=noninteractive

RUN apt update && apt install -y software-properties-common
RUN add-apt-repository ppa:ondrej/php && \
    apt update && \
    apt install -y php7.4 apache2 \
        php7.4-curl \
        php7.4-gd \
        php7.4-mbstring \
        php7.4-xml \
        php7.4-zip \
        curl \
        unzip \
        git \
        # Image optimization tools
        jpegoptim \
        optipng \
        pngquant \
        gifsicle \
        webp \
        libavif-bin \
        nodejs \
        npm \
        && rm -rf /var/lib/apt/lists/*

# Install SVGO globally
RUN npm install -g svgo

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Remove default Apache content
RUN rm -rf /var/www/html/*

# Copy application files
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html

# Move flag.txt to root directory
RUN mv flag.txt /

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Create and set permissions for upload directories
RUN mkdir -p /var/www/html/uploads /var/www/html/optimized
RUN chmod 777 /var/www/html/uploads /var/www/html/optimized
RUN chown -R www-data:www-data /var/www/html

# Apache config: allow only index.php, uploads/, optimized/
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html\n\
    <Directory /var/www/html>\n\
        Options -Indexes\n\
        AllowOverride None\n\
        Require all granted\n\
    </Directory>\n\
    <Directory /var/www/html/uploads>\n\
        Require all granted\n\
    </Directory>\n\
    <Directory /var/www/html/optimized>\n\
        Require all granted\n\
    </Directory>\n\
    <FilesMatch "(^composer\.json$|^composer\.lock$|^Dockerfile$|^\.env$)">\n\
        Require all denied\n\
    </FilesMatch>\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

EXPOSE 80

CMD service apache2 start && tail -f /var/log/apache2/access.log