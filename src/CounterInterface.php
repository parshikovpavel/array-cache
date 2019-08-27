<?php

namespace ppCache;

/**
 * For counters it provides the ability to increment and decrement a cache key atomically.
 *
 * Support for atomic counters which are essential to implement security features
 * like login-attempt counters and overall counting things in a high concurrency environment.
 *
 * Note that the atomic counter functionality is provided as a separate interface
 * (CounterInterface) since not all backends are able to provide it, and it is
 * not possible to build it on top of PSR-6 methods which do not provide atomic
 * guarantees.
 *
 * This interface [was removed](https://github.com/php-fig/fig-standards/pull/847/)
 * from [PSR-16: SimpleCache](https://www.php-fig.org/psr/psr-16/).
 */
interface CounterInterface
{
    /**
     * Increment a value atomically in the cache by the given step value and return the new value
     *
     * If the key does not exist, it is initialized to the value of $step.
     * If the value is increased above PHP_INT_MAX the return value is undefined.
     *
     * @param string $key  The cache item key
     * @param int    $step The value to increment by, defaulting to 1
     *
     * @return int|false The new value on success and false on failure
     */
    public function increment($key, $step = 1);
    /**
     * Decrement a value atomically in the cache by the given step value and return the new value
     *
     * If the key does not exist, it is initialized to the value of -$step.
     * If the value is decreased below PHP_INT_MIN the return value is undefined.
     *
     * @param string $key  The cache item key
     * @param int    $step The value to decrement by, defaulting to 1
     *
     * @return int|false The new value on success and false on failure
     */
    public function decrement($key, $step = 1);
}