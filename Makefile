.PHONY: setup up down shell tinker test format analyze migrate dev build clear log \
        prod-setup prod-up prod-down prod-build prod-shell prod-migrate prod-optimize prod-log

# === Development Configuration ===
DC_DEV = docker compose -f docker-compose.dev.yml
APP_DEV = docker exec -it sipos_app_dev

# === Production Configuration ===
DC_PROD = docker compose -f docker-compose.prod.yml
APP_PROD = docker exec -it sipos_app

# Default to development
DC = $(DC_DEV)
APP = $(APP_DEV)

# --- Development Commands ---
setup:
	@cp .env.example .env
	@$(DC_DEV) up -d --build
	@$(APP_DEV) composer install
	@$(APP_DEV) php artisan key:generate
	@$(APP_DEV) php artisan migrate:fresh --seed
	@$(APP_DEV) npm install
	@$(APP_DEV) npm run build

up:
	@$(DC_DEV) up -d

down:
	@$(DC_DEV) down

shell:
	@$(APP_DEV) sh

tinker:
	@$(APP_DEV) php artisan tinker

test:
	@$(APP_DEV) php artisan test

format:
	@$(APP_DEV) ./vendor/bin/pint
	@$(APP_DEV) npm run format

analyze:
	@$(APP_DEV) ./vendor/bin/phpstan analyse

migrate:
	@$(APP_DEV) php artisan migrate

migrate-fresh:
	@$(APP_DEV) php artisan migrate:fresh --seed

seed:
	@$(APP_DEV) php artisan db:seed

dev:
	@$(APP_DEV) npm run dev

build:
	@$(APP_DEV) npm run build

clear:
	@$(APP_DEV) php artisan optimize:clear

log:
	@docker logs -f sipos_app_dev

# --- Production Commands ---
prod-setup:
	@if [ ! -f .env.production ]; then cp .env.production.example .env.production; fi
	@echo "Please configure .env.production before continuing."
	@$(DC_PROD) up -d --build
	@$(APP_PROD) php artisan key:generate --force
	@$(APP_PROD) php artisan migrate --force
	@$(APP_PROD) php artisan optimize

prod-up:
	@$(DC_PROD) up -d

prod-down:
	@$(DC_PROD) down

prod-build:
	@$(DC_PROD) build --no-cache

prod-shell:
	@$(APP_PROD) sh

prod-migrate:
	@$(APP_PROD) php artisan migrate --force

prod-optimize:
	@$(APP_PROD) php artisan optimize
	@$(APP_PROD) php artisan view:cache
	@$(APP_PROD) php artisan config:cache
	@$(APP_PROD) php artisan route:cache

prod-clear:
	@$(APP_PROD) php artisan optimize:clear

prod-log:
	@$(DC_PROD) logs -f

prod-restart:
	@$(DC_PROD) restart

prod-status:
	@$(DC_PROD) ps
