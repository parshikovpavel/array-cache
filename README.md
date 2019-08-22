The package for caching data in memory using a PHP array.

As cached data is only available in the current request the package should be used for mocking the original cache in tests.

# Installation

The recommended method of installing is via Composer.

Run the following command from the project root:

```bash
composer require parshikovpavel/array-cache --dev
```

# Usage

A detailed description of the interfaces implemented in the library can be found in [PSR-6](https://www.php-fig.org/psr/psr-6/) and [PSR-16](https://www.php-fig.org/psr/psr-16/).

Here are some common use cases of the library.

## PSR-6


# Unit testing

There are unit tests in the `./test` directory. You can run all tests with the following command:

```bssh
$ ./vendor/bin/phpunit tests/ --testdox

ppCache\CacheItemPool
 ✔ Throws exception for invalid key
 ✔ Detects missing item
 ✔ Saves detects retrieves an eternal item
 ✔ Detects expired an item

```


