php bin/console generate:doctrine:entities AppBundle
php bin/console generate:doctrine:entities BasketBundle
php bin/console doctrine:schema:update --force
php bin/console fos:js-routing:debug
php bin/console assets:install --symlink
php bin/console assetic:dump
php bin/console cache:clear
