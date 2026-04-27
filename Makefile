COMPOSE_FILE := compose.production.yaml
DC := docker compose -f $(COMPOSE_FILE)

# ─────────────────────────────────────────────
# Helpers
# ─────────────────────────────────────────────

.DEFAULT_GOAL := help

.PHONY: help
help: ## Show available commands
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) \
		| awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[36m%-20s\033[0m %s\n", $$1, $$2}'

# ─────────────────────────────────────────────
# Initial Deployment
# ─────────────────────────────────────────────

.PHONY: init
init: build generate-keys ## Full initial setup: build image then print generated secrets
	@echo ""
	@echo "\033[32m✔ Done.\033[0m Paste the values above into .env.production, then run: make up"

.PHONY: build
build: ## Build the production Docker image
	@echo "\033[33m→ Building production image...\033[0m"
	$(DC) build

.PHONY: generate-keys
generate-keys: ## Generate APP_KEY and JWT_SECRET using artisan (no db/redis needed)
	@echo ""
	@echo "\033[33m→ Generating APP_KEY...\033[0m"
	@echo "APP_KEY=$$($(DC) run --rm --no-deps app php artisan key:generate --show)"
	@echo ""
	@echo "\033[33m→ Generating JWT_SECRET...\033[0m"
	@echo "JWT_SECRET=$$($(DC) run --rm --no-deps app php artisan jwt:secret --show)"
	@echo ""
	@echo "\033[33m→ Generating random password (use for DB_PASSWORD / REDIS_PASSWORD)...\033[0m"
	@echo "RANDOM_PASSWORD=$$(openssl rand -base64 32)"

# ─────────────────────────────────────────────
# Stack Lifecycle
# ─────────────────────────────────────────────

.PHONY: up
up: ## Start all containers in detached mode
	@echo "\033[33m→ Starting production stack...\033[0m"
	$(DC) up -d
	@echo "\033[32m✔ Stack is up.\033[0m"

.PHONY: down
down: ## Stop and remove all containers
	@echo "\033[33m→ Stopping production stack...\033[0m"
	$(DC) down

.PHONY: restart
restart: down up ## Restart the full stack

.PHONY: rebuild
rebuild: ## Rebuild image and restart (use after code changes)
	@echo "\033[33m→ Rebuilding and restarting...\033[0m"
	$(DC) up -d --build
	@echo "\033[32m✔ Rebuild complete.\033[0m"

.PHONY: update
update: ## Pull latest code, rebuild, and run migrations
	@echo "\033[33m→ Pulling latest changes...\033[0m"
	git pull origin main
	$(DC) up -d --build

# ─────────────────────────────────────────────
# Logs
# ─────────────────────────────────────────────

.PHONY: logs
logs: ## Stream logs from all containers
	$(DC) logs -f

.PHONY: logs-app
logs-app: ## Stream logs from the app container
	$(DC) logs -f app

.PHONY: logs-scheduler
logs-scheduler: ## Stream logs from the scheduler container (horizon, reverb, cron)
	$(DC) logs -f scheduler

.PHONY: logs-db
logs-db: ## Stream logs from the database container
	$(DC) logs -f db

.PHONY: logs-redis
logs-redis: ## Stream logs from the Redis container
	$(DC) logs -f redis

# ─────────────────────────────────────────────
# Artisan Shortcuts
# ─────────────────────────────────────────────

.PHONY: migrate
migrate: ## Run database migrations
	@echo "\033[33m→ Running migrations...\033[0m"
	$(DC) exec app php artisan migrate --force

.PHONY: optimize
optimize: ## Clear and rebuild all caches
	@echo "\033[33m→ Optimizing application...\033[0m"
	$(DC) exec app php artisan optimize

.PHONY: tinker
tinker: ## Open an artisan tinker session inside the app container
	$(DC) exec app php artisan tinker

.PHONY: artisan
artisan: ## Run an arbitrary artisan command: make artisan CMD="route:list"
	$(DC) exec app php artisan $(CMD)

# ─────────────────────────────────────────────
# Status
# ─────────────────────────────────────────────

.PHONY: ps
ps: ## Show running container status
	$(DC) ps
