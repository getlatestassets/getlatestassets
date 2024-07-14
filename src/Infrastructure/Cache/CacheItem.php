<?php

declare(strict_types=1);

namespace Org_Heigl\GetLatestAssets\Infrastructure\Cache;

use DateInterval;
use DateTimeImmutable;
use Psr\Cache\CacheItemInterface;

final class CacheItem implements CacheItemInterface
{
    public function __construct(
        private readonly string $key,
        private mixed $value,
        private DateTimeImmutable $expiresAt,
    ) {
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function get(): mixed
    {
        return $this->value;
    }

    public function isHit(): bool
    {
        return new DateTimeImmutable() < $this->expiresAt;
    }

    public function set(mixed $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function expiresAt(?\DateTimeInterface $expiration): static
    {
        if (null === $expiration) {
            $expiration = new DateTimeImmutable();
        }
        $this->expiresAt = DateTimeImmutable::createFromInterface($expiration);

        return $this;
    }

    public function expiresAfter(\DateInterval|int|null $time): static
    {
        if ($time === null) {
            $time = 0;
        }
        if (is_int($time)) {
            $time = new DateInterval('PT' . $time . 'S');
        }

        $this->expiresAt = (new DateTimeImmutable())->add($time);

        return $this;
    }

    public function getExpirationDate(): DateTimeImmutable
    {
        return $this->expiresAt;
    }
}
