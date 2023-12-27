#!/bin/bash

# Check if the .env file exists
if [ -f .env ]; then
    echo "The .env file exists."
else
    echo "The .env file does not exist."
    cp .env.example .env
fi

# Build the application
echo "Building the application..."
docker compose up -d --build

# Install composer dependencies
echo "Installing composer dependencies..."
docker exec dinacom24-app-container composer install

# Retrieve the APP_KEY value from the .env file
app_key=$(grep -oP '^APP_KEY=(.*)$' .env |  grep -oP '(?<==).*')

# Check if the APP_KEY is empty
if [ -z "$app_key" ]; then
    echo "APP_KEY is empty. Generating a new key..."
    docker exec dinacom24-app-container php artisan key:generate

    # Optionally clear cached configuration
    if [ -f bootstrap/cache/config.php ]; then
        docker exec dinacom24-app-container php artisan config:cache
        echo "Configuration cache cleared."
    fi
else
    echo "APP_KEY is already set."
fi

# Run Storage Link
echo "Running storage link..."
docker exec dinacom24-app-container php artisan storage:link

# Install npm dependencies
echo "Installing npm dependencies..."
docker exec dinacom24-app-container npm install

# Build the vite application
echo "Building the vite application..."
docker exec dinacom24-app-container npm run build

# Clear the npm cache
echo "Clearing the npm cache..."
docker exec dinacom24-app-container npm cache clean --force

# Remove the node_modules directory
echo "Removing the node_modules directory..."
docker exec dinacom24-app-container rm -rf node_modules
