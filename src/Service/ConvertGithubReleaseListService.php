<?php

declare(strict_types=1);

namespace Org_Heigl\GetLatestAssets\Service;

use Org_Heigl\GetLatestAssets\AssetUrl;
use Org_Heigl\GetLatestAssets\Release\Release;
use Org_Heigl\GetLatestAssets\Release\ReleaseList;
use Psr\Http\Message\ResponseInterface;

class ConvertGithubReleaseListService
{
    public function getReleaseList(ResponseInterface $response) : ReleaseList
    {
        $list = new ReleaseList();

        /** @var array{
         *      assets?: array{
         *          name: string,
         *          browser_download_url: string
         *      }[],
         *      tag_name: string
         * }[] $json
         */
        $json = json_decode($response->getBody()->getContents(), true);
        foreach ($json as $release) {
            if (empty($release['assets'])) {
                continue;
            }

            $version = $release['tag_name'];
            $files = [];

            foreach ($release['assets'] as $asset) {
                $files[] = new AssetUrl($asset['name'], $asset['browser_download_url']);
            }

            $list->addRelease(new Release($version, ...$files));
        }
        return $list;
    }
}
