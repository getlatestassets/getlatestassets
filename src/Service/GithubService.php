<?php

declare(strict_types=1);

namespace Org_Heigl\GetLatestAssets\Service;

use DateInterval;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use Org_Heigl\GetLatestAssets\Domain\Version;
use Org_Heigl\GetLatestAssets\Exception\TroubleWithGithubApiAccess;
use Org_Heigl\GetLatestAssets\Links;
use Org_Heigl\GetLatestAssets\Release\ReleaseList;
use Org_Heigl\GetLatestAssets\ReleaseListHydrator;
use Org_Heigl\GetLatestAssets\ReleaseListSerializer;
use Psr\Cache\CacheItemPoolInterface;

class GithubService
{
    public function __construct(
        private readonly Client $client,
        private readonly VersionService $versionService,
        private readonly ConvertGithubReleaseListService $converterService,
        private readonly CacheItemPoolInterface $cacheItemPool,
    ) {
    }

    /**
     * @throws TroubleWithGithubApiAccess
     * @throws \Org_Heigl\GetLatestAssets\Exception\NoAssetMatchingConstraintFound
     */
    public function __invoke(
        string $user,
        string $project,
        string $file,
        string $constraint = null
    ) : Uri {
        $cache = $this->cacheItemPool->getItem('github_' . $user . '_' . $project);
        if (! $cache->isHit()) {
            $releases = new ReleaseList();
            $nextUrl = sprintf(
                '/repos/%1$s/%2$s/releases',
                $user,
                $project
            );
            do {
                try {
                    $result = $this->client->get($nextUrl);
                } catch (Exception $e) {
                    throw new TroubleWithGithubApiAccess(
                        'Something went south while accessing the Github-API',
                        400,
                        $e
                    );
                }

                $releases = $this->converterService->addToReleaseList($releases, $result);

                $header = $result->getHeader('link');
                $nextUrl = null;
                if ($header !== []) {
                    $link = Links::fromHeader($result->getHeader('link')[0]);
                    $nextUrl = $link->getLink('next')?->url;
                }
            } while ($nextUrl !== null);
            $cache->set((new ReleaseListSerializer())->serialize($releases));
            $cache->expiresAfter(new DateInterval('P1D'));
            $this->cacheItemPool->save($cache);
        }

        /**
         * @var array{
         *     version: string,
         *     urls: array{
         *         name: string,
         *         url: string
         *     }[]
         * }[] $c
         */
        $c = $cache->get();
        $result = (new ReleaseListHydrator())->fromArray($c);

        $asset = $this->versionService->getLatestAssetForConstraintFromResult(
            $result,
            $constraint
        );

        $version = new Version($asset->getVersion());
        $rewriter = new FilenameRewriteService($version);

        return new Uri($asset->getAssetUrl(($rewriter)($file))->getAssetUrl());
    }
}
