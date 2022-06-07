#!/usr/bin/env bash

ifndef VERBOSE
.SILENT:
endif


DOCKER_COMPOSE  = docker-compose
DOCKER          = docker
EXEC_PHP        = $(DOCKER_COMPOSE) exec -T backend
EXEC_MYSQL		= $(DOCKER_COMPOSE) exec -T db
ARTISAN         = $(EXEC_PHP) php artisan

IMAGE_AUDIT = jakzal/phpqa:php8.0-alpine
QA = docker run --rm -v `pwd`/:/project -w /project $(IMAGE_AUDIT)

COMPOSER = ${QA} composer

REF_BRANCH ?= 'master'

DOCKER_DB_ROOT_PASSWORD := $(shell cat ../docker-compose.yml|grep MYSQL_ROOT_PASSWORD|cut -d ":" -f2 |sed -e 's/^[ \t]*//')
DATABASE :=  $(shell cat .env.example|grep DB_DATABASE|cut -d "=" -f2 |sed -e 's/^[ \t]*//')
DATABASE_USER :=  $(shell cat .env.example|grep DB_USERNAME|cut -d "=" -f2 |sed -e 's/^[ \t]*//')

ifndef QUIET
#normal mode display text
QUIET_PARAM :=
define display
	@{ \
    printf $1; \
    }
endef
else
#  quiet enabled do nothing
QUIET_PARAM :=  -q
define display
endef
endif

#
# Presentation commands
# -------------------
#
coffee:
	printf "\033[32m You can go take a coffee while we work for you \033[0m\n"

banner:
	printf "\033[95m								\n"
	printf "\033[95m███╗   ███╗ ██████╗ ███╗   ██╗ ██████╗ ██╗     ██╗████████╗██╗  ██╗                                \n"
	printf "\033[95m████╗ ████║██╔═══██╗████╗  ██║██╔═══██╗██║     ██║╚══██╔══╝██║  ██║                                \n"
	printf "\033[95m██╔████╔██║██║   ██║██╔██╗ ██║██║   ██║██║     ██║   ██║   ███████║                                \n"
	printf "\033[95m██║╚██╔╝██║██║   ██║██║╚██╗██║██║   ██║██║     ██║   ██║   ██╔══██║                                \n"
	printf "\033[95m██║ ╚═╝ ██║╚██████╔╝██║ ╚████║╚██████╔╝███████╗██║   ██║   ██║  ██║                                \n"
	printf "\033[95m╚═╝     ╚═╝ ╚═════╝ ╚═╝  ╚═══╝ ╚═════╝ ╚══════╝╚═╝   ╚═╝   ╚═╝  ╚═╝\n"
	printf "\033[95m								\n"
	printf "\033[95m████████╗ ██████╗                                                                                  \n"
	printf "\033[95m╚══██╔══╝██╔═══██╗                                                                                 \n"
	printf "\033[95m   ██║   ██║   ██║                                                                                 \n"
	printf "\033[95m   ██║   ██║   ██║                                                                                 \n"
	printf "\033[95m   ██║   ╚██████╔╝                                                                                 \n"
	printf "\033[95m   ╚═╝    ╚═════╝\n"
	printf "\033[95m								\n"
	printf "\033[95m███╗   ███╗██╗ ██████╗██████╗  ██████╗ ███████╗███████╗██████╗ ██╗   ██╗██╗ ██████╗███████╗███████╗\n"
	printf "\033[95m████╗ ████║██║██╔════╝██╔══██╗██╔═══██╗██╔════╝██╔════╝██╔══██╗██║   ██║██║██╔════╝██╔════╝██╔════╝\n"
	printf "\033[95m██╔████╔██║██║██║     ██████╔╝██║   ██║███████╗█████╗  ██████╔╝██║   ██║██║██║     █████╗  ███████╗\n"
	printf "\033[95m██║╚██╔╝██║██║██║     ██╔══██╗██║   ██║╚════██║██╔══╝  ██╔══██╗╚██╗ ██╔╝██║██║     ██╔══╝  ╚════██║\n"
	printf "\033[95m██║ ╚═╝ ██║██║╚██████╗██║  ██║╚██████╔╝███████║███████╗██║  ██║ ╚████╔╝ ██║╚██████╗███████╗███████║\n"
	printf "\033[95m╚═╝     ╚═╝╚═╝ ╚═════╝╚═╝  ╚═╝ ╚═════╝ ╚══════╝╚══════╝╚═╝  ╚═╝  ╚═══╝  ╚═╝ ╚═════╝╚══════╝╚══════╝\n"
	printf "\033[95m								\n"



#
.DEFAULT_GOAL := help
help: banner ## this help
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/  \x1b\[32m##/\x1b\[33m/'
.PHONY: help coffee banner


##
## Project
## -------

build: ## Build docker
	printf "\033[32m Deploy to Server \033[0m\n"
	$(DOCKER_COMPOSE) build

run: build ## Run project
	printf " 🏃\033[33m Running application ... \033[0m\n"
	$(DOCKER_COMPOSE) pull
	$(DOCKER_COMPOSE) up -d
	# wait 10s for containers being fully started
	sleep 10s
	printf "\n\n"

stop: ## Stop the VMs
	printf " \033[31m◉\033[0m\033[33m  Stopping application ... \033[0m\n"
	$(DOCKER_COMPOSE) stop
	printf "\n\n"

down: ## Stop the VMs
	printf " \033[31m◉\033[0m\033[33m  Stopping application ... \033[0m\n"
	$(DOCKER_COMPOSE) down --volumes
	printf "\n\n"

connect: banner ## Connect to apache docker
	printf " 👷\033[33m  Application shell ... \033[0m\n"
	$(DOCKER_COMPOSE) exec backend bash
	printf "\033[33mBye \033[0m\n\n"

add-hooks:
	 rm -fr .git/hooks && ln -s `pwd`/.hooks/ .git/hooks

require-nginx:
ifndef NGINX_PROXY_DIRECTORY
	printf " \033[31m  You should install nginx proxy ... \033[0m\n"
	printf " \n"
	printf " \033[31m cd ~/Projects && mkdir nginx-proxy && cd nginx-proxy \033[0m\n"
	printf " \033[31m wget -q https://github.com/benjy8001/nginx-proxy/raw/master/bin/installer -O -|bash \033[0m\n"
	printf " \n"
	exit 1
endif

nginx-setup: require-nginx
	if [ ! -f $(NGINX_PROXY_DIRECTORY)/docker/vhost.d/backend.micro.test ]; then \
		echo 'client_max_body_size 10m;' > $(NGINX_PROXY_DIRECTORY)/docker/vhost.d/backend.micro.test;\
	fi; \


start-nginx-proxy: nginx-setup add-certificates
	nginx_proxy

add-certificates: require-nginx
ifeq ("$(wildcard ${NGINX_PROXY_DIRECTORY}/docker/certs/backend.micro.test.crt)","")
	echo "generating nginx proxy certs"
	if [ ! -f ${NGINX_PROXY_DIRECTORY}/docker/certs/AUTHORITY.crt ]; then \
		nginx_proxy_ssl gen_authority;\
	fi;\
	nginx_proxy_ssl gen backend.micro.test
	nginx_proxy_ssl gen frontend.micro.test
	nginx_proxy_ssl gen mailer.micro.test
	nginx_proxy restart
else
	echo "certs aldready added"
endif

login-sudo: # demande le login utilisateur
	printf "\033[32m Please enter your password \033[0m\n"
	sudo printf "\033[32m thanxs \033[0m\n"

host-manager: login-sudo
	wget -q https://gitlab.com/snippets/1730128/raw -O host-manager
	chmod +x host-manager

add-hosts:host-manager
	sudo ./host-manager -add mailer-dev.local.dev 127.0.0.1
	sudo ./host-manager -add backend.micro.test 127.0.0.1

remove-hosts:login-sudo
	sudo ./host-manager -remove mailer-dev.local.dev
	sudo ./host-manager -remove backend.micro.test

.env:
	cp .env.example .env
	#$(ARTISAN) key:generate

start: add-hooks add-hosts .env run vendor assets init-storage permission init-database passport-install seed ## Run project

.PHONY: start-nginx-proxy add-certificates nginx-setup

##
## Config Caching
## -----------------
config-cache: ## Boosting performance
	$(ARTISAN) config:cache

config-clear: ## Clear the config cache
	$(ARTISAN) config:clear

##
## Route Caching
## -----------------
route-cache: ## Boosting performance
	$(ARTISAN) route:cache

route-clear: ## Clear the route cache
	$(ARTISAN) route:clear

##
## Optimization
## -----------------
classmap-optimize: ## Classmap Optimization
	$(ARTISAN) optimize --force

composer-optimize: ## Composer Optimization
	$(COMPOSER) dumpautoload -o

##
## Vendor and Assets
## -----------------
permission: ## Fix permission dir
	$(PHP) chmod 777 storage -R
	$(PHP) chmod 777 bootstrap/cache -R

composer.lock: ## Run composer update
	$(COMPOSER) update --lock --no-interaction --no-suggest

vendor: composer.lock .env ## Run composer install
	printf " 💽\033[33m Start Composer ... \033[0m\n"
	$(COMPOSER) install ${QUIET_PARAM}

composer-validate:
	printf " 💽\033[33m Start Composer ... \033[0m\n"
	$(COMPOSER) validate ${QUIET_PARAM}

composer-install:
	printf " 💽\033[33m Start Composer ... \033[0m\n"
	$(COMPOSER) install ${QUIET_PARAM}

assets: ## Compile assets
	$(DOCKER) run  --rm -v `pwd`/:/project -w /project -u $(id -u):$(id -g) node:lts-alpine npm install
	mkdir -p .npm/cache/
	$(DOCKER) run  --rm -v `pwd`/:/project -w /project -u $(id -u):$(id -g) node:lts-alpine npm run --cache .npm/cache dev

init-storage: ## Symlink storage dir
	$(DOCKER_COMPOSE) exec backend php artisan storage:link

passport-install: ## passport:install
	$(DOCKER_COMPOSE) exec backend php artisan passport:install

migration: ## Generate a new eloquent migration
	$(ARTISAN) make:migration new_migration_file

migrate-sql: ## Play latest eloquent migrations and export to SQL queries
	$(ARTISAN) migrate --pretend $(QUIET_PARAM)

migrate: ## Play latest eloquent migrations
	$(ARTISAN) migrate $(QUIET_PARAM)

seed: ## Populate DB
	$(ARTISAN) db:seed

db-validate-schema: ## Validate the doctrine ORM mapping
	printf " 💽\033[33m TODO \033[0m\n"
	#$(ARTISAN) doctrine:schema:validate

init-database: ## Delete and create database with user
	$(call display," 💽\033[33m Initialize database ... \033[0m\n")
	# on donne à l'utilisateur les droits de créer et supprimer des base de données
	$(EXEC_MYSQL) bash -c "MYSQL_PWD=$(DOCKER_DB_ROOT_PASSWORD) /usr/bin/mysql -u root -e \"GRANT CREATE, DROP ON *.* TO '$(DATABASE_USER)'@'%'; FLUSH PRIVILEGES;\""
	# on supprime et recrée la base de données pour que tout soit propre
	$(ARTISAN) migrate:fresh
	# et on donne à l'utilisateur tous les droits sur la base de données
	$(EXEC_MYSQL) bash -c "MYSQL_PWD=$(DOCKER_DB_ROOT_PASSWORD) /usr/bin/mysql -u root -e \"GRANT ALL PRIVILEGES ON $(DATABASE).* TO '$(DATABASE_USER)'@'%'; FLUSH PRIVILEGES;\""

.PHONY: assets vendor composer.lock

lint: lp lt ly

lt: ## lint twig files
	$(QA) find . -iname '*.twig' -exec  twig-lint  -q lint {} \;

ly: ## lint yaml files
	$(QA) sh -c  "find -iname '*.yml' -not -path './vendor/*' -not -path './node_modules/*' -print0 | xargs -0 -n1  yaml-lint;"

lp: ## lint php files
	$(QA) parallel-lint --blame --exclude vendor .

security: ## Check security of your dependencies (https://security.sensiolabs.org/)
	$(QA) local-php-security-checker

psalm: ## Finds errors in PHP applications
	$(QA) psalm --find-dead-code

rector: ## Tool for instant code upgrades and refactoring
	$(QA) rector process src --dry-run

phpmd: ## PHP Mess Detector (https://phpmd.org)
	$(QA) phpmd src text .phpmd.xml

phpcpd: ## PHP Copy/Paste Detector (https://github.com/sebastianbergmann/phpcpd)
	$(QA) phpcpd src

php_codesnifer: ## PHP_CodeSnifer (https://github.com/squizlabs/PHP_CodeSniffer)
	$(QA) phpcs -d memory_limit=-1 --standard=.phpcs.xml src

phpmetrics: artefacts  ## PhpMetrics (http://www.phpmetrics.org)
	$(QA) phpmetrics --report-html=$(LOCAL_ARTEFACTS)/phpmetrics src

php-cs-fixer: ## php-cs-fixer (http://cs.sensiolabs.org)
	$(QA) php-cs-fixer fix --dry-run --using-cache=no --verbose --diff

apply-php-cs-fixer-all: ## php-cs-fixer (http://cs.sensiolabs.org)
	$(QA) php-cs-fixer fix --using-cache=no --verbose --diff

apply-php-cs-fixer: ## apply php-cs-fixer fixes on new files
	$(eval NEW_FILES := $(shell git diff $(REF_BRANCH) --name-only| egrep '.php$$'))
	$(QA) sh -c 'for filename in $(NEW_FILES);  do if [ -f $$filename ]; then  echo  "fixing $$filename";  php-cs-fixer fix -q --using-cache=no $$filename; fi; done;'

phpstan: ## PHP Static Analysis Tool (https://github.com/phpstan/phpstan)
	$(QA) phpstan analyse -l 0 -c .phpstan.neon --memory-limit=-1 src

twigcs: ## twigcs (https://github.com/friendsoftwig/twigcs)
	$(eval NEW_FILES := $(shell git diff $(REF_BRANCH) --name-only| egrep '.twig$$'))
	$(QA) sh -c 'for filename in $(NEW_FILES);  do if [ -f $$filename ]; then  echo  "analysing $$filename"; twigcs lint  --severity=error $$filename; fi; done;'

phpunit: vendor ## Run phpunit
	printf " 💽\033[33m Start PHPUnit ... \033[0m\n"
	$(QA) phpdbg -qrr /tools/phpunit -c . --colors=never --exclude-group excluded,functionnal

deploy:
	printf "\033[32m Deploy to Server \033[0m\n"
