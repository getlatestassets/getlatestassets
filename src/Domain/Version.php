<?php

declare(strict_types=1);

namespace Org_Heigl\GetLatestAssets\Domain;

final class Version
{
    private const PATTERN = [
        '(?P<major>[\d]+)',
        '\.',
        '(?P<minor>[\d]+)',
        '\.',
        '(?P<patch>[\d]+)',
        '(-(?P<preRelease>[a-zA-Z0-9-]+))?',
        '(\+(?P<build>[a-zA-Z0-9-]+))?',
    ];

    private const GENERIC_PATTERN = [
        '(?P<major>[\d]+)',
        '[^0-9]+',
        '(?P<minor>[\d]+)',
    ];

    private int $major = 0;

    private int $minor = 0;

    private int $patch = 0;

    private string $preRelease = '';

    private string $build = '';

    private bool $isSemVer = true;

    public function __construct(private string $version)
    {
        if (! preg_match(
            '/^' . implode('', self::PATTERN) . '$/',
            $this->version,
            $parts
        )) {
            $this->isSemVer = false;

            if (! preg_match(
                '/' . implode('', self::GENERIC_PATTERN) . '/',
                $this->version,
                $parts
            )) {
                return;
            }
        }

        $this->major = (int)$parts['major'];
        $this->minor = (int)$parts['minor'];
        $this->patch = (int)($parts['patch'] ?? 0);
        $this->preRelease = $parts['preRelease']??'';
        $this->build = $parts['build']??'';
    }

    public function getMajor(): int
    {
        return $this->major;
    }

    public function getMinor(): int
    {
        return $this->minor;
    }

    public function getPatch(): int
    {
        return $this->patch;
    }

    public function getPreRelease(): string
    {
        return $this->preRelease;
    }

    public function getBuild(): string
    {
        return $this->build;
    }

    public function isSemVer(): bool
    {
        return $this->isSemVer;
    }

    public function __toString(): string
    {
        return $this->version;
    }
}
