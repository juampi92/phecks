# Phecks: PHP Checks

[![Latest Version on Packagist](https://img.shields.io/packagist/v/juampi92/phecks.svg?style=flat-square)](https://packagist.org/packages/juampi92/phecks)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/juampi92/phecks/run-tests?label=tests)](https://github.com/juampi92/phecks/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/juampi92/phecks/Check%20&%20fix%20styling?label=code%20style)](https://github.com/juampi92/phecks/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/juampi92/phecks.svg?style=flat-square)](https://packagist.org/packages/juampi92/phecks)

To-do, describe this package.

## Installation

You can install the package via composer:

```bash
composer require juampi92/phecks
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="phecks:config"
```

This is the contents of the published config file:

```php
return [
    'checks' => [],
    'baseline' => '.phecks.baseline.json',
];
```

## Usage

```bash
php artisan phecks:run
```

## Testing

```bash
composer test
```

## Contribution

List of tasks missing for the release:

- [ ] Improve Console formatter to list tips and errors in a clearer way (more similar to PHPStan).
- [ ] Make a progress bar for the checks.
- [ ] Make an ArtisanSource (php artisan list).
- [x] Figure out how to use the filter + map together. Solution: split into two methods like PHPStan does.
- [ ] Finish the idea of Extractors. Pipe? Transformers?
- [ ] Wiki!
- [ ] More tests!

Ideas for future releases:

- Be able to run only one Check.
- Allow configuration of Checks (using the config).

## Credits

- [juampi92](https://github.com/juampi92)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
