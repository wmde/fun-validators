# If the first argument is "composer"...
ifeq (composer,$(firstword $(MAKECMDGOALS)))
  # use the rest as arguments for "composer"
  RUN_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
  # ...and turn them into do-nothing targets
  $(eval $(RUN_ARGS):;@:)
endif

.PHONY: ci test phpunit cs stan covers composer

ci: phpunit cs

cs: phpcs stan

phpunit:
	docker-compose run --rm fun-validators ./vendor/bin/phpunit

phpcs:
	docker-compose run --rm fun-validators ./vendor/bin/phpcs

fix-cs:
	docker-compose run --rm fun-validators ./vendor/bin/phpcbf

stan:
	docker-compose run --rm fun-validators ./vendor/bin/phpstan analyse --level=1 --no-progress src/ tests/

composer:
	docker run --rm --interactive --tty --volume $(shell pwd):/app -w /app\
	 --volume ~/.composer:/composer --user $(shell id -u):$(shell id -g) composer composer $(filter-out $@,$(MAKECMDGOALS))
