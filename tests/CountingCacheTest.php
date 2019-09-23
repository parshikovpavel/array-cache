<?php

namespace ppCache;

use PHPUnit\Framework\TestCase;

/**
 * CountingCache test cases
 */
final class CountingCacheTest extends TestCase
{
    /**
     * @var Cache A simple cache instance
     */
    private $cache;


    protected function setUp(): void
    {
        $this->cache = new CountingCache();
    }

    public function testIncrementsAValue(): void
    {
        $key = 'key';
        $step = 123;

        $value1 = $this->cache->increment($key);
        $this->assertSame(1, $value1);
        $this->assertSame(1, $this->cache->get($key));

        $value2 = $this->cache->increment($key, $step);
        $this->assertSame($value1 + $step, $value2);
        $this->assertSame($value1 + $step, $this->cache->get($key));
    }

    public function testDecrementsAValue(): void
    {
        $key = 'key';
        $value1 = 234;
        $step = 34;

        $this->cache->set($key, $value1);

        $value2 = $this->cache->decrement($key, $step);
        $this->assertSame($value1 - $step, $value2);
        $this->assertSame($value1 - $step, $this->cache->get($key));
    }
}