<?php

declare(strict_types=1);

namespace Org_Heigl\GetLatestAssets;

final class Links
{
    /**
     * @var array<string, Link>
     */
    private array $links;
    private function __construct(Link ...$links)
    {
        foreach ($links as $link) {
            $this->links[$link->relation] = $link;
        }
    }
    public static function fromHeader(string $header): Links
    {
        $links = [];
        foreach (explode(",", $header) as $line) {
            $links[] = Link::fromString($line);
        }
        return new self(...$links);
    }

    public function getLink(string $relation): Link|null
    {
        return $this->links[$relation]??null;
    }
}
