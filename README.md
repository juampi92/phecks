<div align="center">
    <p>
        <h1>
            Phecks
        </h1>
    </p>
</div>

<p align="center">
    <a href="https://juanpablo2.gitbook.io/phecks/" target="_blank">Documentation</a> |
    <a href="#credits">Credits</a>
</p>

<p align="center">
    <a href="https://packagist.org/packages/juampi92/phecks"><img src="https://img.shields.io/packagist/v/juampi92/phecks.svg?style=flat-square" alt="Latest Version on Packagist"></a>
    <a href="https://packagist.org/packages/juampi92/phecks"><img src="https://img.shields.io/packagist/dm/juampi92/phecks.svg?style=flat-square" alt="Downloads Per Month"></a>
    <a href="https://github.com/juampi92/phecks/actions?query=workflow%3Arun-tests+branch%3Amain"><img src="https://img.shields.io/github/workflow/status/juampi92/phecks/run-tests?label=tests&style=flat-square" alt="GitHub Tests Action Status"></a>
    <a href="https://packagist.org/packages/juampi92/phecks"><img src="https://img.shields.io/packagist/php-v/juampi92/phecks.svg?style=flat-square" alt="PHP from Packagist"></a>
</p>

---

Phecks (stands for PHP-Checks) is a custom Check Runner. It will run custom checks in your codebase and will make a report of violations that need fixing.

## What is it for?

- For big teams to align on a styleguide beyond linting.
- Used as an extra set of eyes during the Code Review.
- Teams make their own checks according to their architectural decisions and styleguides.

[See what checks are for](https://juanpablo2.gitbook.io/phecks/about-phecks/what-is-a-check).

On its own, Phecks doesn't contain any checks.
You and your team are responsible for defining and implementing these checks based on your architectural decisions.

Phecks will provide you with a structure to develop and run these checks easily.

## Installation

You can install the package via composer:

```bash
composer require juampi92/phecks --dev
```

Read the full installation instructions in the [documentation](https://juanpablo2.gitbook.io/phecks/).

## Usage

Create a Check to make sure your team's architectural decisions are respected:

```php
/**
 * @implements Check<ReflectionClass>
 */
class ConsoleClassesMustBeSuffixedWithCommandCheck implements Check
{
    public function __construct(
        private readonly ClassSource $source,
    ) {}

    /**
     * This method will get all the possible matches.
     */
    public function getMatches(): MatchCollection
    {
        return $this->source
            ->directory('./app/Console')
            ->run()
            ->reject(fn (ReflectionClass $class): bool => $class->isAbstract())
            ->pipe(new WhereExtendsClassFilter(\Illuminate\Console\Command::class));
    }

    /**
     * processMatch will check if the matches are
     * actual violations, and format them properly.
     */
    public function processMatch($match, FileMatch $file): array
    {
        if (Str::endsWith($match->getName(), 'Command')) {
            return [];
        }

        return [
            ViolationBuilder::make()->message('Command classes must be suffixed with \'Command\''),
        ];
    }
}
```

Add the class in the config `phecks.checks`

And run the following:

```bash
php artisan phecks:run
```

![](docs/error-output.png)

## Testing

```bash
composer test
```

## Credits

- [juampi92](https://github.com/juampi92)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
