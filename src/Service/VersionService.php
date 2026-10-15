<?php

declare(strict_types=1);

namespace Org_Heigl\GetLatestAssets\Service;

use Composer\Semver\Comparator;
use Composer\Semver\Semver;
use Composer\Semver\VersionParser;
use Org_Heigl\GetLatestAssets\Domain\TagNormalizer;
use Org_Heigl\GetLatestAssets\Exception\NoAssetMatchingConstraintFound;
use Org_Heigl\GetLatestAssets\Release\Release;
use Org_Heigl\GetLatestAssets\Release\ReleaseList;

class VersionService
{
    public function getLatestAssetForConstraintFromResult(
        ReleaseList $list,
        ?string $versionConstraint = null
    ) : Release {
        $candidates = [];
        /** @var \Org_Heigl\GetLatestAssets\Release\Release $item */
        foreach ($list as $item) {
            $version = $item->getNormalizedVersion();
            if (null === $version) {
                continue;
            }

            if (null === $versionConstraint) {
                if ('stable' !== VersionParser::parseStability($version)) {
                    continue;
                }
            } elseif (! Semver::satisfies($version, $versionConstraint)) {
                continue;
            }

            $candidates[] = $item;
        }

        if (count($candidates) < 1) {
            throw new NoAssetMatchingConstraintFound(sprintf(
                'Could not match Constraint %s',
                $versionConstraint
            ));
        }

        usort($candidates, function (Release $a, Release $b) {
            if (Comparator::greaterThan($a->getNormalizedVersion() ?? '', $b->getNormalizedVersion() ?? '')) {
                return -1;
            }
            if (Comparator::lessThan($a->getNormalizedVersion() ?? '', $b->getNormalizedVersion() ?? '')) {
                return 1;
            }

            return 0;
        });

        return $candidates[0];
    }
}
