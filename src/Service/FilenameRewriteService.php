<?php

declare(strict_types=1);

namespace Org_Heigl\GetLatestAssets\Service;

use Org_Heigl\GetLatestAssets\Domain\Version;

final class FilenameRewriteService
{
    public function __construct(
        private readonly Version $version
    ) {
    }

    public function __invoke(string $filename): string
    {
        return strtr($filename, [
            '%version%' => (string) $this->version,
            '%major%' => $this->version->getMajor(),
            '%minor%' => $this->version->getMinor(),
            '%patch%' => $this->version->getPatch(),
            '%release%' => $this->version->getPreRelease(),
            '%build%' => $this->version->getBuild(),
        ]);
    }
}
