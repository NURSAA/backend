## Initialize backend


1. `composer install`
2. `php bin/console lexik:jwt:generate-keypair`
3. `php bin/console doctrine:schema:update --force`
5. `php bin/console cache:clear`


## Change branch 
1. `composer install`
2. `php bin/console doctrine:schema:update --force`
3. `php bin/console cache:clear`
