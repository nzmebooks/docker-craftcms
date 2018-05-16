#!/bin/bash

# Download the latest Craft (https://craftcms.com/support/download-previous-versions)
echo "Installing latest craft 3 using composer"

# Create Craft project
composer create-project craftcms/craft ./craft/

# Install the yii2-redis library
composer require --prefer-dist yiisoft/yii2-redis -d ./craft/

# Add database environment
cp .env ./craft/.env

# Cleanup
chown -Rf nginx:nginx ./craft
chmod -R a+w ./craft/storage/runtime/compiled_templates

echo "Moving in config"
cp config/* ./craft/config/

echo "Finished!"
