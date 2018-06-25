<?php

declare(strict_types=1);

namespace Org_Heigl\GetLatestAssets\Release;

use Org_Heigl\GetLatestAssets\AssetUrl;
use Org_Heigl\GetLatestAssets\Exception\AssetNotFound;

class Release
{
    private $version;

    private $assetUrls;

    public function __construct(string $version, AssetUrl ...$assetUrls)
    {
        $this->version = $version;
        $this->assetUrls = $assetUrls;
    }

    public function getVersion() : string
    {
        return $this->version;
    }

    public function getAssetUrls() : array
    {
        return $this->assetUrls;
    }

    public function hasAsset(string $name) : bool
    {
        foreach ($this->assetUrls as $url) {
            if ($url->getAssetName() === $name) {
                return true;
            }
        }

        return false;
    }

    public function getAssetUrl(string $name) : AssetUrl
    {
        foreach ($this->assetUrls as $url) {
            if ($url->getAssetName() === $name) {
                return $url;
            }
        }

        throw new AssetNotFound(sprintf(
            'The asset %s could not be found',
            $name
        ));
    }


}
