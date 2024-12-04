FROM php:8.2-apache-bullseye

COPY ini/php.ini /usr/local/etc/php
COPY ini/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
COPY --chown=root:root conf/*.conf /etc/apache2/sites-available

RUN apt update
RUN apt-get install libldap2-dev

RUN docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu/

RUN docker-php-ext-install pdo pdo_mysql ldap

RUN a2enmod rewrite
RUN a2enmod headers

RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

# Disable default site and enable our own
RUN a2dissite 000-default default-ssl
RUN rm /etc/apache2/sites-available/000-default.conf  \
    /etc/apache2/sites-available/default-ssl.conf

# RUN a2dissite
RUN a2ensite grades.conf sharepoint.conf admin.conf marketing.conf

RUN a2enmod ldap authnz_ldap

RUN mkdir -p /root/website-passwords/
RUN echo "Test1234!" > /root/website-passwords/passwd-grades
RUN echo "Test1234!" > /root/website-passwords/passwd-sharepoint
RUN echo "Test1234!" > /root/website-passwords/passwd-admin
RUN echo "Test1234!" > /root/website-passwords/passwd-marketing

RUN chmod 600 /root/website-passwords/*

