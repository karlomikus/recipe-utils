<?php

declare(strict_types=1);

namespace Kami\RecipeUtilsTests;

use Kami\RecipeUtils\Converter;
use PHPUnit\Framework\TestCase;
use Kami\RecipeUtils\RecipeIngredient;
use Kami\RecipeUtils\UnitConverter\Cl;
use Kami\RecipeUtils\UnitConverter\Ml;
use Kami\RecipeUtils\UnitConverter\Oz;
use Kami\RecipeUtils\UnitConverter\Dash;
use Kami\RecipeUtils\UnitConverter\Part;
use Kami\RecipeUtils\UnitConverter\Shot;
use Kami\RecipeUtils\UnitConverter\Units;
use PHPUnit\Framework\Attributes\DataProvider;
use Kami\RecipeUtils\UnitConverter\AmountValue;

class ConverterTest extends TestCase
{
    /**
     * @return array<mixed>
     */
    public static function provideUnitData(): array
    {
        return [
            ['classFrom' => Oz::class, 'input' => '1', 'expected' => 1.0, 'convert' => null],
            ['classFrom' => Oz::class, 'input' => '0.5', 'expected' => 15.0, 'convert' => 'toMl'],
            ['classFrom' => Oz::class, 'input' => '0.5', 'expected' => 1.5, 'convert' => 'toCl'],
            ['classFrom' => Oz::class, 'input' => '1', 'expected' => 96.0, 'convert' => 'toDash'],
            ['classFrom' => Oz::class, 'input' => '1', 'expected' => 1.0, 'convert' => 'toOz'],

            ['classFrom' => Ml::class, 'input' => '30', 'expected' => 30.0, 'convert' => null],
            ['classFrom' => Ml::class, 'input' => '22.5', 'expected' => 0.75, 'convert' => 'toOz'],
            ['classFrom' => Ml::class, 'input' => '22.5', 'expected' => 2.25, 'convert' => 'toCl'],
            ['classFrom' => Ml::class, 'input' => '7.5', 'expected' => 24.0, 'convert' => 'toDash'],
            ['classFrom' => Ml::class, 'input' => '7.5', 'expected' => 7.5, 'convert' => 'toMl'],

            ['classFrom' => Cl::class, 'input' => '3', 'expected' => 3.0, 'convert' => null],
            ['classFrom' => Cl::class, 'input' => '1.5', 'expected' => 0.5, 'convert' => 'toOz'],
            ['classFrom' => Cl::class, 'input' => '2.25', 'expected' => 2.25, 'convert' => 'toCl'],
            ['classFrom' => Cl::class, 'input' => '0.5', 'expected' => 16.0, 'convert' => 'toDash'],
            ['classFrom' => Cl::class, 'input' => '1.5', 'expected' => 15.0, 'convert' => 'toMl'],

            ['classFrom' => Dash::class, 'input' => '4', 'expected' => 1.25, 'convert' => 'toMl'],

            ['classFrom' => Shot::class, 'input' => '1', 'expected' => 30.0, 'convert' => 'toMl'],
            ['classFrom' => Part::class, 'input' => '1', 'expected' => 30.0, 'convert' => 'toMl'],
        ];
    }

    /**
     * @param class-string<\Kami\RecipeUtils\UnitConverter\UnitInterface> $classFrom
     */
    #[DataProvider('provideUnitData')]
    public function testBasicConverting(string $classFrom, string $input, float $expected, ?string $convertToMethod = null): void
    {
        if (!class_exists($classFrom)) {
            $this->markTestIncomplete('Converting from "' . $classFrom . '" is not possible');
        }

        $amountValue = AmountValue::fromString($input);

        if (!$convertToMethod) {
            $this->assertSame($expected, (new $classFrom($amountValue))->getValue());
        } else {
            $this->assertSame($expected, (new $classFrom($amountValue))->{$convertToMethod}()->getValue());
        }
    }

    public function testConverterClass(): void
    {
        $testConvert = Converter::tryConvert(new RecipeIngredient('test', 0.5, 'oz', 'test'), Units::Ml);
        $this->assertSame(15.0, $testConvert->amount);
        $this->assertSame('ml', $testConvert->units);

        $testConvert = Converter::tryConvert(new RecipeIngredient('test', 4.0, 'dash', 'test'), Units::Oz, [Units::Dash]);
        $this->assertSame(4.0, $testConvert->amount);
        $this->assertSame('dash', $testConvert->units);

        $testConvert = Converter::tryConvert(new RecipeIngredient('test', 1.5, '', 'test'), Units::Ml);
        $this->assertSame(1.5, $testConvert->amount);
        $this->assertSame('', $testConvert->units);
    }
}
