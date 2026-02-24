.PHONY: help start stop restart build shell console composer migrate migrations jwt-keys test logs ps

help: ## Show this help
	@awk 'BEGIN {FS = ":.*##"; printf "\nUsage:\n  make \033[36m<target>\033[0m\n\nTargets:\n"} /^[a-zA-Z_-]+:.*?##/ { printf "  \033[36m%-20s\033[0m %s\n", $$1, $$2 }' $(MAKEFILE_LIST)

start: ## Start all containers
	docker compose up -d

stop: ## Stop all containers
	docker compose down

restart: ## Restart all containers
	docker compose restart

build: ## Build and start containers
	docker compose up -d --build

shell: ## Access PHP container shell
	docker compose exec php bash

console: ## Run Symfony console (usage: make console CMD="cache:clear")
	docker compose exec php php bin/console $(CMD)

composer: ## Run composer (usage: make composer CMD="require vendor/package")
	docker compose exec php composer $(CMD)

migrate: ## Run database migrations
	docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction

migrations: ## Generate a new migration from entity changes
	docker compose exec php php bin/console doctrine:migrations:diff

schema-update: ## Force update database schema (dev only)
	docker compose exec php php bin/console doctrine:schema:update --force

jwt-keys: ## Regenerate JWT keys
	docker compose exec php sh -c ' \
		openssl genpkey -algorithm RSA -out config/jwt/private.pem \
			-aes256 -pass pass:"$$JWT_PASSPHRASE" -pkeyopt rsa_keygen_bits:4096 2>/dev/null && \
		openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem \
			-pubout -passin pass:"$$JWT_PASSPHRASE" 2>/dev/null && \
		chmod 600 config/jwt/private.pem && \
		echo "JWT keys regenerated."'

cache-clear: ## Clear Symfony cache
	docker compose exec php php bin/console cache:clear

test: ## Run PHPUnit tests
	docker compose exec php php bin/phpunit

logs: ## Show container logs
	docker compose logs -f

ps: ## Show container status
	docker compose ps
