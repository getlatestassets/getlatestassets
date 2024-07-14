<?php

declare(strict_types=1);

namespace Org_Heigl\GetLatestAssets;

use Org_Heigl\GetLatestAssets\Release\ReleaseList;

final class ReleaseListSerializer
{
    /**
     * @return array{
     *     version: string,
     *     urls: array{
     *         name: string,
     *         url: string
     *     }[]
     * }[]
     */
    public function serialize(ReleaseList $releaseList): array
    {
        $array = [];
        foreach ($releaseList as $release) {
            $urls = [];
            foreach ($release->getAssetUrls() as $assetUrl) {
                $urls[] = [
                    'url' => $assetUrl->getAssetUrl(),
                    'name' => $assetUrl->getAssetName(),
                ];
            }
            $array[] = [
                'version' => $release->getVersion(),
                'urls' => $urls,
            ];
        }

        return $array;
    }
}
