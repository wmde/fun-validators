# fun-validators

[![Build Status](https://travis-ci.org/wmde/fun-validators.svg?branch=master)](https://travis-ci.org/wmde/fun-validators)
[![Latest Stable Version](https://poser.pugx.org/wmde/fun-validators/version.png)](https://packagist.org/packages/wmde/fun-validators)
[![Download count](https://poser.pugx.org/wmde/fun-validators/d/total.png)](https://packagist.org/packages/wmde/fun-validators)

General and shared validation services created as part of the WMDE fundraising software.

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

For development you need to have Docker and compose Docker plugin installed. Local PHP and Composer are not needed.

### Installing dependencies

To install the project dependencies via Composer, run:

    make install-php

To update the dependencies, run

    make update-php


To update a specific dependency, you can run 


    make update-php COMPOSER_FLAGS=dependency-name


### Running the CI checks

To run all CI checks, which includes PHPUnit tests, PHPCS style checks and coverage tag validation, run:

    make
    
### Running the tests

To run just the PHPUnit tests run

    make test

To run only a subset of PHPUnit tests or otherwise pass flags to PHPUnit, run

    docker compose run --rm fun-validators ./vendor/bin/phpunit --filter SomeClassNameOrFilter


