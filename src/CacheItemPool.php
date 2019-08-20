<?php

namespace ppCache;

use Psr\Cache\CacheItemPoolInterface;

/**
 * @inheritDoc
 */
final class CacheItemPool implements CacheItemPoolInterface
{
    /**
     * @var CacheItem[] An array of cached items
     */
    private $items;

    /**
     * @inheritDoc
     */
    public function getItem($key): CacheItem
    {

    }

    /**
     * Validates key of an item
     *
     * The key must consist of at least one character
     * The following characters are reserved for future extensions and MUST NOT be supported by implementing libraries: {}()/\@:
     *
     * @param string $key
     * @return bool
     */
    private function validateKey(string $key): bool
    {
        $unsupportedCharacters = '{}()/\@:';

        if ($key === '' || false !== strpbrk($key, $unsupportedCharacters)) {
            throw new InvalidArgumentException('Invalid key');
        }
    }

}