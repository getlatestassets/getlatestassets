<?php

declare(strict_types=1);

namespace Org_Heigl\GetLatestAssets;

use Org_Heigl\GetLatestAssets\Domain\TagNormalizer;
use Org_Heigl\GetLatestAssets\Release\Release;
use Org_Heigl\GetLatestAssets\Release\ReleaseList;

final class ReleaseListHydrator
{
    /**
     * @param array{
     *     version: string,
     *     urls: array{
     *         name: string,
     *         url: string
     *     }[]
     * }[] $list
     */
    public function fromArray(array $list): ReleaseList
    {
		$tagNormalizer = new TagNormalizer();
        $releaseList = new ReleaseList();
        foreach ($list as $release) {
            $urls = [];
            foreach ($release['urls'] as $assetUrl) {
                $urls[] = new AssetUrl($assetUrl['name'], $assetUrl['url']);
            }
            $releaseList->addRelease(new Release(
				$release['version'],
				$tagNormalizer->normalize($release['version']),
				...$urls
			));
        }

        return $releaseList;
    }
}
