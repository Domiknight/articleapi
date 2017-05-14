#!/usr/bin/env bash

cd /vagrant

## Install Composer Dependencies
php composer.phar install

# Init database
php bin/console doctrine:database:create
php bin/console doctrine:schema:create