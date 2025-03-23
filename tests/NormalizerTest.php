<?php

declare(strict_types=1);

namespace Kami\RecipeUtilsTests;

use PHPUnit\Framework\TestCase;
use Kami\RecipeUtils\Parser\Normalizer\StringNormalizer;

final class NormalizerTest extends TestCase
{
    public function testNormalize(): void
    {
        $normalizer = new StringNormalizer();

        $this->assertSame(
            'Test',
            $normalizer->normalize('Test')
        );

        $this->assertSame(
            'A <p>new</p> ? test',
            $normalizer->normalize('A <p>new</p> ?* test')
        );

        $this->assertSame(
            '1/2',
            $normalizer->normalize('½')
        );

        $this->assertSame(
            'Spac test',
            $normalizer->normalize('Spac   &#9;     test')
        );

        $this->assertSame(
            '7.56 decimal-comma. Helps with parsing, test.',
            $normalizer->normalize('7,56 decimal-comma. Helps with parsing, test.')
        );

        $this->assertSame(
            'This is open ( bracket without closing)',
            $normalizer->normalize('This is open ( bracket without closing')
        );
    }
}
