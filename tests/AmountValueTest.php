<?php

declare(strict_types=1);

namespace Kami\RecipeUtilsTests;

use PHPUnit\Framework\TestCase;
use Kami\RecipeUtils\AmountValue;
use PHPUnit\Framework\Attributes\DataProvider;

final class AmountValueTest extends TestCase
{
    #[DataProvider('provideTestData')]
    public function testParseStringAsAmountValue(string $input, float $output): void
    {
        $this->assertSame($output, AmountValue::fromString($input)->getValue());
    }

    public function testStringable(): void
    {
        $this->assertSame('23.35', (string) new AmountValue(23.3499));
    }

    /**
     * @return array<mixed>
     */
    public static function provideTestData(): array
    {
        return [
            ['1/2', 0.5],
            ['1 1/2', 1.5],
            ['1½', 1.5],
            ['¾', 0.75],
            ['0', 0.0],
            ['1', 1.0],
            ['76,76', 76.76],
            ['1.5', 1.5],
        ];
    }
}
