<?php

declare(strict_types=1);

namespace Org_Heigl\GetLatestAssetsTest\Domain;

use Org_Heigl\GetLatestAssets\Domain\TagNormalizer;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(TagNormalizer::class)]
class TagNormalizerTest extends TestCase
{
    #[DataProvider('normalizeProvider')]
    public function testNormalize(string $tag, ?string $expected): void
    {
        self::assertSame($expected, (new TagNormalizer())->normalize($tag));
    }

    /**
     * @return array<array{string, string|null}>
     */
    public static function normalizeProvider(): array
    {
        return [
            ['1.2.3', '1.2.3'],
            ['v1.2.3', '1.2.3'],
            ['release-78.3', '78.3'],
            ['release-79.1rc', '79.1rc'],
            ['release-77-1', '77.1'],
            ['icu-64-2', '64.2'],
            ['1.2.3-beta1', '1.2.3-beta1'],
            ['2026a', '2026.1'],
            ['2026c', '2026.3'],
            ['2025z', '2025.26'],
            ['tzdata-2026c', '2026.3'],
            ['nightly', null],
            ['release-', null],
            ['latest-build-foo', null],
        ];
    }
}
