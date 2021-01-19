BASEDIR=$( realpath $(dirname $0 ))
cd $BASEDIR

[ ! -e src/.env ] && cp src/.env.example src/.env

docker-compose up -d
docker-compose exec app php artisan key:generate
docker-compose exec -u www-data app touch /var/www/storage/database.sqlite
docker-compose exec -u www-data app php artisan migrate:fresh
docker-compose exec -u www-data app php artisan db:seed

