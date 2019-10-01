The package for caching data in memory using a PHP array.

As cached data is only available in the current request the package should be used for mocking the original cache in tests.

The cache implementation is compatible with [PSR-6](https://www.php-fig.org/psr/psr-6/)/[PSR-16](https://www.php-fig.org/psr/psr-16/). 
In addition, `CounterInterface` ([excluded](https://github.com/php-fig/fig-standards/pull/847/) from PSR-16) is included in the library and used for implementation.

The table below shows the correspondence between the library classes and the implemented interfaces.

| PSR | Class | Implements |
| --- | --- | --- |
| [PSR-6](https://www.php-fig.org/psr/psr-6/) | `CacheItemPool` | `CacheItemPoolInterface` |
| [PSR-6](https://www.php-fig.org/psr/psr-6/) | `CacheItem`     | `CacheItemInterface` |
| [PSR-16](https://www.php-fig.org/psr/psr-16/) | `Cache` | `CacheInterface` |
| [PSR-16](https://www.php-fig.org/psr/psr-6/) + [excluded](https://github.com/php-fig/fig-standards/pull/847/)&nbsp;`CounterInterface` | `CountingCache` | `CacheInterface` + `CounterInterface` |

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

According to PSR-6, the package provides `\ppCache\CacheItemPool` class (implementing the `\PSR\Cache\CacheItemPoolInterface` interface) and
`\ppCache\CacheItem` class (implementing the `\PSR\Cache\CacheItemInterface` interface).

### Cache fixture

Use `\ppCache\CacheItemPool` instance as a fixture. 
Put the creating of the cache fixture into the setUp() method.

```php
final class CacheTest extends TestCase
{
    private $itemPool;

    protected function setUp(): void
    {
        $this->itemPool = new \ppCache\CacheItemPool();
    }
    
    /* ... */
}
```

### Dependency injection

If your application based on the dependency inversion principle and the dependency injection technique, it's very easy
to replace a real cache with a mock cache. 

Consider the case of constructor injection. Let's assume `Client` class provide a parameter in a constructor to inject a cache instance. 

```php
final class CacheTest extends TestCase
{
    /* ... */
    
    public function testFeature(): void
    {
        $client = new Client($this->itemPool);
        
        /* ... */
    }
}
```

### Get value from cache

The most common cache use case is a getting value and computing in case of cache miss. 

A cache client must first try to get value from cache by `$key`.
 
* If trying is successful, return `$value`

* otherwise compute `$value` by calling the time-consuming `compute()` function and cache the
value for a while

```php
final class Client {

    private $itemPool;

    public function __construct(\Psr\Cache\CacheItemPoolInterface $itemPool)
    {
        $this->itemPool = $itemPool;
    }

    private function getValue(string $key, int $ttl = 3600): string
    {
        $item = $this->itemPool->getItem($key);
        if (!$item->isHit()) {
            $value = $this->compute();
            $item->set($value);
            $item->expiresAfter($ttl);
            $this->itemPool->save($item);
        }
        return $item->get();
    }

    /* ... */
}
```

## PSR-16

According to PSR-16, the package provides `\ppCache\Cache` class (implementing the `\Psr\SimpleCache\CacheInterface` interface).

### Cache fixture

Use `\ppCache\Cache` instance as a fixture. 
Put the creating of the cache fixture into the setUp() method.

```php
final class CacheTest extends TestCase
{
    private $cache;

    protected function setUp(): void
    {
        $this->cache = new \ppCache\Cache();
    }
    
    /* ... */
}
```

### Dependency injection

Similarly, inject a cache instance into the client constructor:

```php
final class CacheTest extends TestCase
{
    /* ... */
    
    public function testFeature(): void
    {
        $client = new Client($this->cache);
        
        /* ... */
    }
}
```

### Get value from cache

The algorithm is the same as the one for `\ppCache\CacheItemPool` but the implementation is a bit simpler.

```php
final class Client
{
    private $cache;

    public function __construct(\Psr\SimpleCache\CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    private function getValue(string $key, int $ttl = 3600): string
    {
        if (null === ($value = $this->cache->get($key))) {
            $value = $this->compute();
            $this->cache->set($key, $value, $ttl);
        }

        return $value;
    }

    /* ... */
}
```


# Unit testing

There are unit tests in the `./tests` directory. You can run all tests with the following command:

```bash
$ ./vendor/bin/phpunit tests/ --testdox

ppCache\CacheItemPool
 ✔ Throws exception for invalid key
 ✔ Detects missing item
 ✔ Saves detects retrieves an eternal item
 ✔ Detects an expired item

```


