FROM php:7.4-apache
# Install system dependencies
# Sheznie
RUN apt-get update && \
           apt-get install -y vim unzip curl openssl
# Install system dependencies library
RUN apt-get install -y libonig-dev libxml2-dev zlib1g-dev libpng-dev libjpeg-dev libzip-dev libpq-dev

# Install PHP extensions
RUN docker-php-ext-install bcmath zip
# Install PGSQL extension
RUN docker-php-ext-install pdo_pgsql

RUN docker-php-ext-configure gd \
    && docker-php-ext-install gd

RUN a2enmod rewrite
RUN curl -sS https://getcomposer.org/installer | php \
  && chmod +x composer.phar && mv composer.phar /usr/local/bin/composer
COPY ./000-default.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

COPY . /var/www/html
COPY .env.example .env
#COPY 000-default.conf /etc/apache2/sites-available

RUN composer install

RUN php artisan passport:keys
RUN php artisan key:generate

##RUN service apache2 restart
RUN chmod 777 -R /var/www && chmod +x ./entrypoint.sh

#Setup supervisor
RUN apt-get install -y supervisor
COPY srm_queue_worker.conf /etc/supervisor/conf.d

EXPOSE 80

COPY USERTrust_RSA_Certification_Authority.crt /usr/local/share/ca-certificates/USERTrust_RSA_Certification_Authority.crt
RUN chmod 644 /usr/local/share/ca-certificates/USERTrust_RSA_Certification_Authority.crt
RUN update-ca-certificates

ENTRYPOINT ["./entrypoint.sh"]
