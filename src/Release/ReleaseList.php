<?php

declare(strict_types=1);

namespace Org_Heigl\GetLatestAssets\Release;

use Iterator;
use Org_Heigl\IteratorTrait\IteratorTrait;

/**
 * @implements Iterator<int, Release>
 */
class ReleaseList implements Iterator
{
    use IteratorTrait;

    /**
     * @var Release[]
     */
    private array $releases = [];

    public function addRelease(Release $release) : void
    {
        $this->releases[] = $release;
    }

    /**
     * Get the array the iterator shall iterate over.
     *
     * @return Release[]
     */
    protected function & getIterableElement(): array
    {
        return $this->releases;
    }
}
