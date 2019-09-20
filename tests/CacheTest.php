<?php

namespace ppCache;

use PHPUnit\Framework\TestCase;

/**
 * @inheritDoc
 */
final class CacheTest extends TestCase
{
    /**
     * @var Cache A simple cache instance
     */
    private $cache;


    protected function setUp(): void
    {
        $this->cache = new Cache();
    }

    public function testThrowsExceptionForInvalidKey(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->cache->set('{braces_unsupported}', 0);
    }

    public function testDetectsMissingItem(): void
    {
        $key = 'missing_item';
        $default = 0;

        $this->assertFalse($this->cache->has($key));
        $this->assertNull($this->cache->get($key));
        $this->assertSame($default, $this->cache->get($key, $default));
    }

    public function testSavesDetectsRetrievesAnEternalItem(): void
    {
        $key = 'new_item';
        $value = 'item_value';
        $missingItemKey = 'missing_item';

        $this->cache->set($key, $value);

        $this->assertTrue($this->cache->has($key));
        $this->assertSame($value, $this->cache->get($key));
        $this->assertSame([$key => $value], $this->cache->getMultiple([$key]));
        $this->assertSame([$key => $value, $missingItemKey => null], $this->cache->getMultiple([$key, $missingItemKey]));
    }

    public function testDetectsAnExpiredItem(): void
    {
        $key = 'new_item';
        $value = 'item_value';
        $ttl = 1;

        $this->cache->set($key, $value, $ttl);

        sleep($ttl + 1);

        $this->assertFalse($this->cache->has($key));
        $this->assertNull($this->cache->get($key));
    }

    public function testGetsItemsWithASpecifiedExpirationTime(): void
    {
        $key = 'new_item';
        $value = 'item_value';
        $ttl = 10;

        $this->cache->set($key, $value, $ttl);

        $this->assertSame($value, $this->cache->get($key));
        $this->assertTrue($this->cache->has($key));
    }

    public function testSupportsMultipleFunctions(): void
    {
        $items = [
            'key1' => 'value1',
            'key2' => 2,
            'key3' => true
        ];

        //TODO

        setMultiple($values, $ttl = null);
        getMultiple($keys, $default = null);
        deleteMultiple($keys);
        clear();

    }

    public function testPerformsDeletionAndClearing(): void
    {
        $items = [
            'key1' => 'value1',
            'key2' => null,
            'key3' => [5],
            'key4' => 3.14
        ];

        //TODO


    }

}