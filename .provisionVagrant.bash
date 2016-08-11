#!/bin/bash
echo "Provisioning box..."
echo "Installing laravel dependencies with composer..."
cd /var/www/taskmanager
composer install

echo "Creating .env..."
sudo cp ../.env.scotchbox .env
sudo cp ../scotchbox.local.conf /etc/apache2/sites-available/
sudo a2ensite scotchbox.local.conf
sudo service apache2 restart

php artisan key:generate > /dev/null

echo "Installing dependencies with npm..."
sudo chown -R $(whoami) $(npm config get prefix)/{lib/node_modules,bin,share} > /dev/null
#Upgrade to latest npm
sudo npm install -g npm
#Upgrade to latest node
sudo npm cache clean -f
sudo npm install -g n
sudo n stable

sudo npm install #install required node modules / packages

