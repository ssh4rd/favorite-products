.PHONY: help build up down restart logs install migrate seed test clean fresh

# Default target
help: ## Show this help message
	@echo "Available commands:"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "  %-15s %s\n", $$1, $$2}'

# Docker commands
build: ## Build the Docker images
	docker-compose build

up: ## Start all services
	docker-compose up -d

down: ## Stop all services
	docker-compose down

restart: ## Restart all services
	docker-compose restart

logs: ## Show logs from all services
	docker-compose logs -f

logs-app: ## Show logs from app service only
	docker-compose logs -f app

logs-nginx: ## Show logs from nginx service only
	docker-compose logs -f nginx

logs-db: ## Show logs from database service only
	docker-compose logs -f db

# Application setup commands
install: ## Install PHP and Node dependencies
	docker-compose exec app composer install
	docker-compose exec app npm install

migrate: ## Run database migrations
	docker-compose exec app php artisan migrate

migrate-fresh: ## Drop all tables and re-run all migrations
	docker-compose exec app php artisan migrate:fresh

seed: ## Seed the database
	docker-compose exec app php artisan db:seed

# Development commands
dev: ## Start development servers (Laravel + Vite)
	docker-compose exec app php artisan serve --host=0.0.0.0 --port=8000 &
	docker-compose exec app npm run dev

shell: ## Access the app container shell
	docker-compose exec app sh

shell-db: ## Access the database container shell
	docker-compose exec db mysql -u favorite_user -p favorite_products

cache-clear: ## Clear all Laravel caches
	docker-compose exec app php artisan cache:clear
	docker-compose exec app php artisan config:clear
	docker-compose exec app php artisan route:clear
	docker-compose exec app php artisan view:clear

key-generate: ## Generate Laravel application key
	docker-compose exec app php artisan key:generate

# Testing
test: ## Run PHP tests
	docker-compose exec app php artisan test

# Cleanup
clean: ## Remove all containers and volumes
	docker-compose down -v --remove-orphans

fresh: ## Complete fresh start (clean + build + up + install + migrate)
	make clean
	make build
	make up
	sleep 10
	make install
	make migrate
	@echo "Application is ready at http://localhost:8000"

# Quick start for development
start-dev: ## Quick start for development (up + install + migrate + dev)
	make up
	sleep 5
	make install
	make migrate
	make dev
