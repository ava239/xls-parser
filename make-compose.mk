compose:
	heroku local -f Procfile.dev

compose-up:
	docker-compose up

compose-bash:
	docker-compose run app bash

compose-setup: compose-build
	docker-compose run app make setup

compose-build:
	docker-compose build

compose-migrate:
	docker-compose run app make migrate

compose-db:
	docker-compose exec db mysql -u parser -h db --password=parser

compose-down:
	docker-compose down -v
