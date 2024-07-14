<?php

declare(strict_types=1);

namespace Org_Heigl\GetLatestAssets;

final class Link
{
    private function __construct(
        public readonly string $url,
        public readonly string $relation
    ) {
    }
    public static function fromString(string $header): Link
    {
        $data = explode(';', $header);
        $url = str_replace(['<','>'], ['', ''], trim($data[0]));
        $relation = str_replace(['rel=', '"'], ['', ''], trim($data[1]));

        return new self($url, $relation);
    }
}
