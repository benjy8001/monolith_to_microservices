#!/usr/bin/env bash

DOCKER_COMPOSE_OPTION="-T"
DOCKER_OPTION=""

npm() {
   docker run $DOCKER_OPTION --network=nginx-proxy --rm -v `pwd`/:/project -w /project node:lts-alpine npm "$@"
}

phpqa() {
   docker run $DOCKER_OPTION --network=nginx-proxy --rm -v `pwd`/:/project -w /project jakzal/phpqa:php8.0-alpine "$@"
}

composer-unused() {
    phpqa composer-unused -vvv
}

phpunit () {
    phpqa phpdbg -qrr /tools/phpunit -c . --colors=never --exclude-group excluded,functionnal $@
}

artisan() {
   phpqa php artisan $@
}

symfony () {
   docker run --rm -v $(pwd):$(pwd) -w $(pwd) symfonycorp/cli $@
}

composer () {
   docker run  --rm -v `pwd`/:/project -w /project jakzal/phpqa:php8.0-alpine composer $@
}

php-cs-fixer() {
  phpqa php-cs-fixer fix --using-cache=no --verbose --diff $@
}

php-cs-fixer-fix() {
  phpqa php-cs-fixer fix --using-cache=no --verbose $@
}
