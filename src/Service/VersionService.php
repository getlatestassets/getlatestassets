<?php

declare(strict_types=1);

namespace Org_Heigl\GetLatestAssets\Service;

use Composer\Semver\Comparator;
use Composer\Semver\Semver;
use Org_Heigl\GetLatestAssets\Exception\NoAssetMatchingConstraintFound;
use Org_Heigl\GetLatestAssets\Release\Release;
use Org_Heigl\GetLatestAssets\Release\ReleaseList;

class VersionService
{
    public function getLatestAssetForConstraintFromResult(ReleaseList $list, string $versionConstraint = null) : Release
    {
        $newList = [];
        /** @var \Org_Heigl\GetLatestAssets\Release\Release $item */
        foreach ($list as $item) {
            if (null !== $versionConstraint &&  ! Semver::satisfies($item->getVersion(), $versionConstraint)) {
                continue;
            }

            $newList[] = $item;
        }

        if (count($newList) < 1) {
            throw new NoAssetMatchingConstraintFound(sprintf(
                'Could not match Constraint %s',
                $versionConstraint
            ));
        }

        usort($newList, function(Release $a, Release $b) {
            if (Comparator::greaterThan($a->getVersion(), $b->getVersion())) {
                return -1;
            }
            if (Comparator::lessThan($a->getVersion(), $b->getVersion())) {
                return 1;
            }

            return 0;
        });

        return $newList[0];
    }
}
