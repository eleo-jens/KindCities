echo yes | del migrations
symfony console make:migration --no-interaction
symfony console doctrine:migration:migrate --no-interaction