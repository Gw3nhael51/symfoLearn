.PHONY: help db db-create migration migrate start stop restart test watch

help:
	@echo "Commandes disponibles :"
	@echo "  make db          - Ouvre la console psql dans le conteneur PostgreSQL"
	@echo "  make start       - Demarre Docker et le serveur de developpement Symfony"
	@echo "  make stop        - Arrete le serveur Symfony et les conteneurs Docker"
	@echo "  make restart     - Redemarre les conteneurs et le serveur web"
	@echo "  make migration   - Cree une nouvelle migration Doctrine"
	@echo "  make migrate     - Execute les migrations Doctrine"
	@echo "  make test        - Lance la suite de tests PHPUnit"
	@echo "  make watch       - Compile le CSS Tailwind en continu"

db:
	docker compose exec database psql app app

start:
	docker compose up -d
	symfony server:start -d

stop:
	symfony server:stop
	docker compose stop

restart: stop start

db-create:
	symfony console doctrine:database:create --if-not-exists

migration:
	symfony console make:migration

migrate:
	symfony console doctrine:migrations:migrate --no-interaction

test:
	symfony run vendor/bin/phpunit

watch:
	symfony console tailwind:build --watch
