# First, copy src/.env.example to src/.env and then edit it
# Then docker-compose up -d
# Then run this script

docker-compose exec app php artisan key:generate
docker-compose exec -u www-data app touch /var/www/storage/database.sqlite
docker-compose exec -u www-data app php artisan migrate:fresh
docker-compose exec -u www-data app php artisan db:seed

