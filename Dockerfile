FROM php:8.2-apache

# 1. Install ekstensi yang dibutuhkan Laravel
RUN apt-get update -y && apt-get install -y libmariadb-dev unzip zip \
    && docker-php-ext-install pdo pdo_mysql

# 2. Ubah Document Root ke folder /public bawaan Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf

# 3. Aktifkan mod_rewrite agar URL Laravel bisa dibaca
RUN a2enmod rewrite

# 4. Copy semua file proyekmu ke dalam server Render
COPY . /var/www/html

# 5. Install Composer dan paket-paket Laravel
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# 6. Berikan izin akses untuk folder penyimpanan bawaan Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache