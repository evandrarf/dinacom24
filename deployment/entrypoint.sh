#!/bin/bash

# Change ownership of specific directories
chown -R www-data:www-data /var/www/
chmod -R 777 /var/www/storage

# Execute the CMD from the Dockerfile
exec "$@"
