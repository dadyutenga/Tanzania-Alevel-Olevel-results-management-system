FROM almalinux:9

LABEL maintainer="Tanzania Results Management"
LABEL description="AlmaLinux 9 with PHP 8.2 for CodeIgniter 4"

# Install repositories
RUN dnf -y update && \
    dnf -y install epel-release && \
    dnf -y install dnf-plugins-core && \
    dnf -y install https://rpms.remirepo.net/enterprise/remi-release-9.rpm

# Enable PHP 8.2
RUN dnf -y module reset php && \
    dnf -y module enable php:remi-8.2

# Install PHP, nginx and extensions
RUN dnf -y install \
    php \
    php-cli \
    php-fpm \
    php-common \
    php-json \
    php-mbstring \
    php-xml \
    php-mysqlnd \
    php-pdo \
    php-gd \
    php-intl \
    php-zip \
    php-curl \
    php-opcache \
    php-bcmath \
    php-fileinfo \
    php-sodium \
    php-pecl-redis \
    nginx \
    git \
    unzip \
    wget \
    supervisor \
    && dnf clean all

# Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    php -r "unlink('composer-setup.php');"

WORKDIR /var/www/html

# Copy composer files first for better caching
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Copy application
COPY . .

# Complete composer install
RUN composer dump-autoload --optimize --no-dev

# Set permissions
RUN chown -R apache:apache /var/www/html && \
    chmod -R 755 /var/www/html && \
    chmod -R 775 /var/www/html/writable && \
    chmod -R 775 /var/www/html/public

# Configure PHP-FPM
RUN mkdir -p /run/php-fpm && \
    sed -i 's/^listen = .*/listen = 127.0.0.1:9000/' /etc/php-fpm.d/www.conf && \
    sed -i 's/^user = .*/user = apache/' /etc/php-fpm.d/www.conf && \
    sed -i 's/^group = .*/group = apache/' /etc/php-fpm.d/www.conf && \
    sed -i 's/^;catch_workers_output = .*/catch_workers_output = yes/' /etc/php-fpm.d/www.conf

# Copy configurations
COPY docker/supervisor/supervisord.conf /etc/supervisord.conf
COPY docker/php/custom.ini /usr/local/etc/php/conf.d/custom.ini
# Copy nginx default config as a fallback (compose mounts can override it)
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

EXPOSE 80 9000

CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisord.conf"]
