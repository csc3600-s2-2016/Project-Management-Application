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
sudo npm install -g npm # Update npm to the latest version
sudo npm install #install required node modules / packages

