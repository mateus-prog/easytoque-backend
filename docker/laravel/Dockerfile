FROM php:8.0-cli AS builder

# Arguments defined in docker-compose.yml
ARG user
ARG uid

WORKDIR /var/www

RUN apt-get update && \
    apt-get install libzip-dev -y && \
    apt-get update && \
    apt-get install zip unzip -y && \
    docker-php-ext-install zip

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user
    

COPY . .
RUN /usr/bin/composer install

FROM php:8.0-fpm-alpine
WORKDIR /var/www
RUN rm -rf /var/www/html
COPY --from=builder /var/www/laravel .
RUN chown -R www-data:www-data /var/www
RUN ln -s public html
RUN php artisan migrate:fresh --seed
EXPOSE 9000
CMD ["php-fpm"]
