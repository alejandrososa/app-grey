# Executables (local)
DOCKER_COMP = docker-compose

# Docker containers
PHP_CONT = $(DOCKER_COMP) exec php
DB_CONT 	= $(DOCKER_COMP) exec database

# Executables
PHP      = $(PHP_CONT) php
DB		 = $(DB_CONT)
COMPOSER = $(PHP_CONT) composer
SYMFONY  = $(PHP_CONT) bin/console

# Misc
.DEFAULT_GOAL = help
.PHONY        = help build up start down logs sh composer vendor sf cc

## —— 🎵 🐳 The Symfony-docker Makefile 🐳 🎵 ——————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

run: start vendor migrations tests

## —— Docker 🐳 ————————————————————————————————————————————————————————————————
build: ## Builds the Docker images
	@echo "\n==> Building docker images"
	@$(DOCKER_COMP) build --pull

build-no-cache: ## Builds the Docker images
	@echo "\n==> Building docker images --no-cache"
	@$(DOCKER_COMP) build --pull --no-cache

up: ## Start the docker hub in detached mode (no logs)
	@$(DOCKER_COMP) up --detach

start: build up ## Build and start the containers

down: ## Stop the docker hub
	@$(DOCKER_COMP) down --remove-orphans

logs: ## Show live logs
	@echo "\n==> Showing logs"
	@$(DOCKER_COMP) logs --tail=0 --follow

sh-php: ## Connect to the PHP FPM container
	@echo "\n==> Connecting to the PHP FPM container"
	@$(PHP_CONT) sh

sh-db: ## Connect to the Database container
	@echo "\n==> Connecting to the Database container"
	@$(DB_CONT) sh

## —— Composer 🧙 ——————————————————————————————————————————————————————————————
composer: ## Run composer, pass the parameter "c=" to run a given command, example: make composer c='req symfony/orm-pack'
	@echo "\n==> Installing packages"
	@$(eval c ?=)
	@$(COMPOSER) $(c)

vendor: ## Install vendors according to the current composer.lock file
vendor: c=install --prefer-dist --no-progress --no-scripts --no-interaction
vendor: composer

## —— Running Migrations 🧙 ——————————————————————————————————————————————————————————————
migrations: ## Run composer, pass the parameter "c=" to run a given command, example: make composer c='req symfony/orm-pack'
	@echo "\n==> Running Migrations, "
	#@$(PHP_CONT) php bin/console doctrine:database:create --if-not-exists
	#@$(PHP_CONT) php bin/console --env=test doctrine:database:create --if-not-exists
	#@$(PHP_CONT) php bin/console doctrine:migrations:migrate --no-interaction
	#@$(PHP_CONT) php bin/console --env=test doctrine:migrations:migrate --no-interaction


## —— Symfony 🎵 ———————————————————————————————————————————————————————————————
sf: ## List all Symfony commands or pass the parameter "c=" to run a given command, example: make sf c=about
	@$(eval c ?=)
	@$(SYMFONY) $(c)

# This will run phpunit tests
tests: tests-unit tests-behat

# This will run phpunit tests
tests-unit:
	@echo "\n==> Running phpunit tests"
	@$(PHP_CONT) bin/phpunit --testdox

# This will run behat tests
tests-behat:
	@echo "\n==> Running behat tests"
	@@$(PHP_CONT) vendor/bin/behat

cc: c=c:c ## Clear the cache
cc: sf
