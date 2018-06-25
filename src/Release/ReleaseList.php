<?php

declare(strict_types=1);

namespace Org_Heigl\GetLatestAssets\Release;

use Iterator;
use Org_Heigl\IteratorTrait\IteratorTrait;

class ReleaseList implements Iterator
{
    use IteratorTrait;

    private $releases;

    public function addRelease(Release $release) : void
    {
        $this->releases[] = $release;
    }

    /**
     * Get the array the iterator shall iterate over.
     *
     * @return array
     */
    protected function & getIterableElement()
    {
        return $this->releases;
    }
}
