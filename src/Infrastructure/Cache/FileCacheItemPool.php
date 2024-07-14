<?php

declare(strict_types=1);

namespace Org_Heigl\GetLatestAssets\Infrastructure\Cache;

use BadMethodCallException;
use DateTimeImmutable;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

final class FileCacheItemPool implements CacheItemPoolInterface
{
    public function __construct(private readonly string $cacheBaseDir)
    {
    }

    public function getItem(string $key): CacheItemInterface
    {
        if (! file_exists($this->cacheBaseDir . '/' . $key . '.json')) {
            return new CacheItem($key, '', new DateTimeImmutable('-1 hour'));
        }
        $item = file_get_contents($this->cacheBaseDir . '/' . $key . '.json');
        if (false === $item) {
            return new CacheItem($key, '', new DateTimeImmutable('-1 hour'));
        }
        /**
         * @var array{
         *     data: mixed,
         *     expires: string
         * } $data
         */
        $data = json_decode($item, true);

        return new CacheItem($key, $data['data'], new DateTimeImmutable($data['expires']));
    }

    /**
     * @param string[] $keys
     * @return iterable<CacheItemInterface>
     */
    public function getItems(array $keys = []): iterable
    {
        throw new BadMethodCallException('Not implemented');
    }

    public function hasItem(string $key): bool
    {
        return file_exists($this->cacheBaseDir . '/' . $key . '.json');
    }

    public function clear(): bool
    {
        throw new BadMethodCallException('Not implemented');
    }

    public function deleteItem(string $key): bool
    {
        unlink($this->cacheBaseDir . '/' . $key . '.json');

        return file_exists($this->cacheBaseDir . '/' . $key . '.json');
    }

    public function deleteItems(array $keys): bool
    {
        throw new BadMethodCallException('Not implemented');
    }

    public function save(CacheItemInterface $item): bool
    {
        if (! $item instanceof CacheItem) {
            return false;
        }
        $content = [
            'data' => $item->get(),
            'expires' => $item->getExpirationDate()->format(DateTimeImmutable::ATOM),
        ];
        if (! file_exists($this->cacheBaseDir)) {
            mkdir($this->cacheBaseDir, 0777, true);
        }
        file_put_contents(
            $this->cacheBaseDir . '/' . $item->getKey() . '.json',
            json_encode($content, JSON_PRETTY_PRINT)
        );

        return file_exists($this->cacheBaseDir . '/' . $item->getKey() . '.json');
    }

    public function saveDeferred(CacheItemInterface $item): bool
    {
        throw new BadMethodCallException('Not implemented');
    }

    public function commit(): bool
    {
        throw new BadMethodCallException('Not implemented');
    }
}
