.PHONY: setup up down shell tinker test format analyze migrate dev build clear log

# Docker aliases
DC = docker compose -f docker-compose.dev.yml
APP = docker exec -it sipos_app_dev

setup:
	@cp .env.example .env
	@$(DC) up -d --build
	@$(APP) composer install
	@$(APP) php artisan key:generate
	@$(APP) php artisan migrate:fresh --seed
	@$(APP) npm install
	@$(APP) npm run build

up:
	@$(DC) up -d

down:
	@$(DC) down

shell:
	@$(APP) sh

tinker:
	@$(APP) php artisan tinker

test:
	@$(APP) php artisan test

format:
	@$(APP) ./vendor/bin/pint
	@$(APP) npm run format

analyze:
	@$(APP) ./vendor/bin/phpstan analyse

migrate:
	@$(APP) php artisan migrate

migrate-fresh:
	@$(APP) php artisan migrate:fresh --seed

seed:
	@$(APP) php artisan db:seed

dev:
	@$(APP) npm run dev

build:
	@$(APP) npm run build

clear:
	@$(APP) php artisan optimize:clear

log:
	@docker logs -f sipos_app_dev
