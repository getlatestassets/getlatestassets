<?php

declare(strict_types=1);

namespace Org_Heigl\GetLatestAssets\Domain;

use Composer\Semver\VersionParser;
use UnexpectedValueException;

/**
 * Turns a git tag like "release-78.3" or "release-77-1" into a version
 * string that composer/semver understands. Returns null when the tag can
 * not be read as a version.
 */
final class TagNormalizer
{
    public function normalize(string $tag): ?string
    {
        $version = preg_replace('/^[^0-9]+/', '', $tag);
        if (null === $version || '' === $version) {
            return null;
        }

        // Year plus a letter per release in that year, e.g. tzdata's "2026c"
        if (preg_match('/^(\d{4})([a-z])$/', $version, $parts)) {
            $version = $parts[1] . '.' . (ord($parts[2]) - ord('a') + 1);
        }

        // Older tags use dashes between the numbers, e.g. "release-77-1"
        $version = preg_replace('/(?<=\d)-(?=\d)/', '.', $version);
        if (null === $version) {
            return null;
        }

        try {
            new VersionParser()->normalize($version);
        } catch (UnexpectedValueException) {
            return null;
        }

        return $version;
    }
}
