<?php

declare(strict_types=1);

namespace Org_Heigl\GetLatestAssets;

final class AssetUrl
{
    public function __construct(
        private readonly string $name,
        private readonly string $url
    ) {
    }

    public function getAssetName() : string
    {
        return $this->name;
    }

    public function getAssetUrl() : string
    {
        return $this->url;
    }
}
