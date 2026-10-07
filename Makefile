# Digi-Santé Junior — development shortcuts
#
# Run `make` to display the available commands.

DOCKER_COMPOSE = docker compose
CONSOLE = $(DOCKER_COMPOSE) exec app php bin/console
COMPOSER = $(DOCKER_COMPOSE) exec app composer

.DEFAULT_GOAL := help

.PHONY: help install up up-build down restart php console composer cc \
       migration migrate validate fixtures reset-db lint

help: ## Display available commands
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[36m%-12s\033[0m %s\n", $$1, $$2}'

install: ## Install and initialize the development environment
	$(DOCKER_COMPOSE) up -d --build
	$(COMPOSER) install
	$(CONSOLE) doctrine:database:create --if-not-exists
	$(CONSOLE) doctrine:migrations:migrate --no-interaction
	@echo ""
	@echo "Application : http://localhost:8081"
	@echo "phpMyAdmin  : http://localhost:8082"

up: ## Start Docker containers
	$(DOCKER_COMPOSE) up -d

up-build: ## Build and start Docker containers
	$(DOCKER_COMPOSE) up -d --build

down: ## Stop and remove Docker containers
	$(DOCKER_COMPOSE) down

restart: ## Restart Docker containers
	$(DOCKER_COMPOSE) restart

php: ## Open a shell inside the PHP application container
	$(DOCKER_COMPOSE) exec app bash

console: ## Run a Symfony command (example: make console ARGS="about")
	$(CONSOLE) $(ARGS)

composer: ## Run Composer (example: make composer ARGS="show")
	$(COMPOSER) $(ARGS)

cc: ## Clear the Symfony cache
	$(CONSOLE) cache:clear

migration: ## Generate a migration from entity changes
	$(CONSOLE) make:migration

migrate: ## Apply pending migrations
	$(CONSOLE) doctrine:migrations:migrate --no-interaction

validate: ## Validate Doctrine mapping and database schema
	$(CONSOLE) doctrine:schema:validate

fixtures: ## Reload demo data (existing data will be deleted)
	$(CONSOLE) doctrine:fixtures:load --no-interaction

reset-db: ## Recreate the database and reload demo data
	$(CONSOLE) doctrine:database:drop --force --if-exists
	$(CONSOLE) doctrine:database:create
	$(CONSOLE) doctrine:migrations:migrate --no-interaction
	$(CONSOLE) doctrine:fixtures:load --no-interaction

lint: ## Check Twig, YAML and the Symfony service container
	$(CONSOLE) lint:twig templates
	$(CONSOLE) lint:yaml config
	$(CONSOLE) lint:container
