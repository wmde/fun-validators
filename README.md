# fun-validators

[![Build Status](https://travis-ci.org/wmde/fun-validators.svg?branch=master)](https://travis-ci.org/wmde/fun-validators)
[![Latest Stable Version](https://poser.pugx.org/wmde/fun-validators/version.png)](https://packagist.org/packages/wmde/fun-validators)
[![Download count](https://poser.pugx.org/wmde/fun-validators/d/total.png)](https://packagist.org/packages/wmde/fun-validators)

General and shared validation services created as part of the WMDE fundraising software.

We moved away from this form of validators in favour of a simpler approach though are still
using the ones in this library in some of our older UseCases. 

## Installation

To use the fun-validators library in your project, simply add a dependency on wmde/fun-validators
to your project's `composer.json` file. Here is a minimal example of a `composer.json`
file that just defines a dependency on fun-validators 1.x:

```json
{
    "require": {
        "wmde/fun-validators": "~1.0"
    }
}
```

## Development

For development you need to have Docker and Docker-compose installed. Local PHP and Composer are not needed.

    sudo apt-get install docker docker-compose

### Running Composer

To pull in the project dependencies via Composer, run:

    make composer install

You can run other Composer commands via `make run`, but at present this does not support argument flags.
If you need to execute such a command, you can do so in this format:

    docker run --rm --interactive --tty --volume $PWD:/app -w /app\
     --volume ~/.composer:/composer --user $(id -u):$(id -g) composer composer install -vvv

### Running the CI checks

To run all CI checks, which includes PHPUnit tests, PHPCS style checks and coverage tag validation, run:

    make
    
### Running the tests

To run just the PHPUnit tests run

    make test

To run only a subset of PHPUnit tests or otherwise pass flags to PHPUnit, run

    docker-compose run --rm app ./vendor/bin/phpunit --filter SomeClassNameOrFilter
