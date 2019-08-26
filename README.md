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

Here are some common usage patterns of the library for testing your application using [PHPUnit](https://phpunit.de/).

## PSR-6

### Cache fixture

Use `ppCache\CacheItemPool` instance as a fixture. 
Put the creating of the cache fixture into the setUp() method.

```php
class CacheTest extends TestCase
{
    protected $itemPool;

    protected function setUp(): void
    {
        $this->itemPool = new ppCache\CacheItemPool();
    }
    
    /* ... */
}
```

### Get value from cache

Try to get value from cache by `$key`. 
* If trying is successful, return `$value`
* otherwise compute `$value` by calling the time-consuming `compute()` function and cache 
value for 1 hour 

```php

``` 

# Unit testing

There are unit tests in the `./test` directory. You can run all tests with the following command:

```bssh
$ ./vendor/bin/phpunit tests/ --testdox

ppCache\CacheItemPool
 ✔ Throws exception for invalid key
 ✔ Detects missing item
 ✔ Saves detects retrieves an eternal item
 ✔ Detects an expired item

```


