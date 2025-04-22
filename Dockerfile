# Usar una imagen oficial de PHP
FROM php:8.1-apache

# Activar el módulo de reescritura de Apache
RUN a2enmod rewrite

# Copiar tus archivos PHP al contenedor
COPY . /var/www/html/

# Exponer el puerto 80
EXPOSE 80
