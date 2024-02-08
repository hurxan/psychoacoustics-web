# Use an Apache base image with PHP 7.4
FROM php:7.4-apache

# Copy the contents of your PHP application to the /var/www/html directory in the container
# COPY /Users/senyuva/workspace/psychoacoustics-web/ /var/www/html

# Expose port 80 to allow outside access to the web server
EXPOSE 80