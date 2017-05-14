#!/usr/bin/env bash

# PHP
if ! [ -f /etc/php/5.6/apache2/php.ini.orig ]; then
    # Apache
    cp /etc/php/5.6/apache2/php.ini /etc/php/5.6/apache2/php.ini.orig
    sed -e 's/;date\.timezone =/date\.timezone = Australia\/Melbourne/' < /etc/php/5.6/apache2/php.ini > /etc/php/5.6/apache2/php.ini.new
    mv /etc/php/5.6/apache2/php.ini.new /etc/php/5.6/apache2/php.ini

    # CLI
    cp /etc/php/5.6/apache2/php.ini /etc/php/5.6/cli/php.ini.orig
    sed -e 's/;date\.timezone =/date\.timezone = Australia\/Melbourne/' < /etc/php/5.6/cli/php.ini > /etc/php/5.6/cli/php.ini.new
    mv /etc/php/5.6/cli/php.ini.new /etc/php/5.6/cli/php.ini
fi

# Apache
if ! [ -f /etc/apache2/sites-available/000-default.conf.orig ]; then

    # Make apache run as the vagrant user - solves perms issues with shared folders
    echo "Make apache run as vagrant user"
    cp /etc/apache2/envvars /etc/apache2/envvars.orig
    sed -e 's/www\-data/vagrant/' < /etc/apache2/envvars > /etc/apache2/envvars.new
    mv /etc/apache2/envvars.new /etc/apache2/envvars

    # Update the default security model to point to /vagrant share
    cp /etc/apache2/apache2.conf /etc/apache2/apache2.conf.orig
    sed -e 's/Directory \/var\/www/Directory \/vagrant/' < /etc/apache2/apache2.conf > /etc/apache2/apache2.conf.new
    mv /etc/apache2/apache2.conf.new /etc/apache2/apache2.conf

    # Point the Document Root to the right place
    cp /etc/apache2/sites-available/000-default.conf /etc/apache2/sites-available/000-default.conf.orig
    cp /vagrant/scripts/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
fi

# load the changes
a2enmod rewrite
service apache2 restart

# MySQL Backups
mkdir -p /opt/backups/database
crontab /vagrant/scripts/crontab

