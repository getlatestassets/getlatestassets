<?php

declare(strict_types=1);

namespace Org_Heigl\GetLatestAssets\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use Org_Heigl\GetLatestAssets\Exception\AssetNotFound;
use Org_Heigl\GetLatestAssets\Exception\NoAssetsFound;
use Org_Heigl\GetLatestAssets\Exception\TroubleWithGithubApiAccess;
use Exception;

class GithubService
{
    private $client;

    private $versionService;

    private $converterService;

    public function __construct(
        Client $client,
        VersionService $versionService,
        ConvertGithubReleaseListService $converterService
    ) {
        $this->client = $client;
        $this->versionService = $versionService;
        $this->converterService = $converterService;
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
        try {
            $result = $this->client->get(sprintf(
                '/repos/%1$s/%2$s/releases',
                $user,
                $project
            ));
        } catch (Exception $e) {
            throw new TroubleWithGithubApiAccess(
                'Something went south while accessing the Github-API',
                400,
                $e
            );
        }

        $result = $this->converterService->getReleaseList($result);

        $asset = $this->versionService->getLatestAssetForConstraintFromResult(
            $result,
            $constraint
        );

        return new Uri($asset->getAssetUrl($file)->getAssetUrl());
    }
}
