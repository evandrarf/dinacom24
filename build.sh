docker compose up -d --build
docker exec dinacom24-app-container composer install
docker exec dinacom24-app-container php artisan key:generate --ansi
docker exec dinacom24-app-container php artisan storage:link
docker exec dinacom24-app-container npm install
docker exec dinacom24-app-container npm run build
# docker exec dinacom24-app-container rm -rf node_modules
