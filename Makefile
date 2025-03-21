current_user   := $(shell id -u)
current_group  := $(shell id -g)
BUILD_DIR      := $(PWD)
DOCKER_FLAGS   := --interactive --tty
DOCKER_IMAGE   := registry.gitlab.com/fun-tech/fundraising-frontend-docker
COVERAGE_FLAGS := --coverage-html coverage

install-php:
	docker run --rm $(DOCKER_FLAGS) --volume $(BUILD_DIR):/app -w /app --volume ~/.composer:/composer --user $(current_user):$(current_group) $(DOCKER_IMAGE) composer install $(COMPOSER_FLAGS)

update-php:
	docker run --rm $(DOCKER_FLAGS) --volume $(BUILD_DIR):/app -w /app --volume ~/.composer:/composer --user $(current_user):$(current_group) $(DOCKER_IMAGE) composer update $(COMPOSER_FLAGS)

ci: phpunit cs stan

ci-with-coverage: phpunit-with-coverage cs stan

phpunit:
	docker compose run --rm fun-validators ./vendor/bin/phpunit

phpunit-with-coverage:
	docker compose run --rm -e XDEBUG_MODE=coverage fun-validators ./vendor/bin/phpunit $(COVERAGE_FLAGS)

cs:
	docker compose run --rm fun-validators ./vendor/bin/phpcs

fix-cs:
	docker compose run --rm fun-validators ./vendor/bin/phpcbf

stan:
	docker compose run --rm fun-validators ./vendor/bin/phpstan analyse --level=9 --no-progress src/ tests/

 .PHONY: install-php update-php ci ci-with-coverage phpunit phpunit-with-coverage cs fix-cs stan
