<?php

declare(strict_types=1);

namespace Org_Heigl\GetLatestAssets;

class AssetUrl
{
    private $name;

    private $url;

    public function __construct(string $name, string $url)
    {
        $this->name = $name;
        $this->url = $url;
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
