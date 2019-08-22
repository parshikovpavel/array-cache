<?php

namespace ppCache;

use PHPUnit\Framework\TestCase;

/**
 * @inheritDoc
 */
final class CacheItemPoolTest extends TestCase
{
    public function testThrowsExceptionForInvalidKey(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $cacheItemPool = new CacheItemPool();
        $cacheItemPool->getItem('(parenthesis_unsupported)');
    }

    public function testDetectsMissingItem(): void
    {
        $key = 'missing_item';
        $cacheItemPool = new CacheItemPool();

        $this->assertFalse($cacheItemPool->hasItem($key));

        $cacheItem = $cacheItemPool->getItem($key);
        $this->assertFalse($cacheItem->isHit());
        $this->assertNull($cacheItem->get());
    }

    public function testSavesDetectsRetrievesAnEternalItem(): void
    {
        $key = 'new_item';
        $value = 'item_value';

        $cacheItemPool = new CacheItemPool();
        $cacheItem = $cacheItemPool->getItem($key);
        $cacheItem->set($value);

        $cacheItemPool->save($cacheItem);

        $this->assertTrue($cacheItemPool->hasItem($key));
        $cacheItem = $cacheItemPool->getItem($key);
        $this->assertTrue($cacheItem->isHit());
        $this->assertSame($value, $cacheItem->get());
    }

    public function testDetectsExpiredAnItem(): void
    {
        $key = 'new_item';
        $value = 'item_value';

        $cacheItemPool = new CacheItemPool();
        $cacheItem = $cacheItemPool->getItem($key);
        $cacheItem->set($value)->expiresAfter(1);

        $cacheItemPool->save($cacheItem);
        sleep(2);

        $this->assertFalse($cacheItemPool->hasItem($key));
        $cacheItem = $cacheItemPool->getItem($key);
        $this->assertFalse($cacheItem->isHit());
        $this->assertNull($cacheItem->get());
    }
}