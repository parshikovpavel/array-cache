<?php

namespace ppCache;

use PHPUnit\Framework\TestCase;

/**
 * Cache test cases
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
        $this->assertSame([
            $key => $value,
            $missingItemKey => null
        ], $this->cache->getMultiple([$key, $missingItemKey]));
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
            'key3' => true,
            'key4' => 2.73
        ];

        $this->cache->setMultiple($items);
        $this->assertSame($items, $this->cache->getMultiple(array_keys($items)));

        $keysToDelete = [
            'key2',
            'key4'
        ];

        $this->cache->deleteMultiple($keysToDelete);
        $this->assertEquals(
            array_fill_keys($keysToDelete, null) + $items,
            $this->cache->getMultiple(array_keys($items))
        );
    }

    public function testPerformsDeletionAndClearing(): void
    {
        $items = [
            'key1' => 'value1',
            'key2' => null,
            'key3' => [5],
            'key4' => 3.14
        ];

        $this->cache->setMultiple($items);

        $keyToDelete = 'key3';
        $this->cache->delete($keyToDelete);
        $this->assertEquals([$keyToDelete => null] + $items, $this->cache->getMultiple(array_keys($items)));

        $this->cache->clear();
        $this->assertSame(array_fill_keys(array_keys($items), null), $this->cache->getMultiple(array_keys($items)));
    }
}
