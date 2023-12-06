<?php

declare(strict_types=1);

namespace Kami\RecipeUtilsTests;

use PHPUnit\Framework\TestCase;
use Kami\RecipeUtils\Parser\Parser;
use Kami\RecipeUtils\Parser\UnitParser;
use Kami\RecipeUtils\UnitConverter\Units;

final class ParserTest extends TestCase
{
    public function testParse(): void
    {
        $parser = new Parser();

        $testCases = [
            '30 ml Tequila reposado' => [
                'amount' => '30',
                'units' => 'ml',
                'name' => 'Tequila reposado',
            ],
            '4 ounces (½ cup) ginger beer' => [
                'amount' => '4',
                'units' => 'oz',
                'name' => 'ginger beer',
            ],
            '0.5 oz John D. Taylor’s Velvet Falernum' => [
                'amount' => '0.5',
                'units' => 'oz',
                'name' => 'John D. Taylor\'s Velvet Falernum',
            ],
            '1 1/2 oz. mezcal (Talbert uses Del Maguey Vida)' => [
                'amount' => '1 1/2',
                'units' => 'oz',
                'name' => 'mezcal',
                'comment' => 'Talbert uses Del Maguey Vida',
            ],
            '1 sliced strawberry' => [
                'amount' => '1',
                'units' => 'slice',
                'name' => 'strawberry',
            ],
            '2-3 mint sprigs' => [
                'amount' => '2-3',
                'units' => 'sprigs',
                'name' => 'mint',
            ],
            '2 to 3 mint sprigs' => [
                'amount' => '2 - 3',
                'units' => 'sprigs',
                'name' => 'mint',
            ],
            '2 or 3 mint sprigs' => [
                'amount' => '2 - 3',
                'units' => 'sprigs',
                'name' => 'mint',
            ],
            '2 oz. spiced rum' => [
                'amount' => '2',
                'units' => 'oz',
                'name' => 'spiced rum',
            ],
            'Maraschino cherries' => [
                'amount' => '0',
                'units' => '',
                'name' => 'Maraschino cherries',
            ],
            'barspoon Pedro Ximenez' => [
                'amount' => '0',
                'units' => 'barspoon',
                'name' => 'Pedro Ximenez',
            ],
            '2-3 large basil leaves' => [
                'amount' => '2-3',
                'units' => 'leaves',
                'name' => 'large basil',
            ],
            '2 Dashes Angostura Bitters' => [
                'amount' => '2',
                'units' => 'dash',
                'name' => 'Angostura Bitters',
            ],
            '30 ml' => [
                'amount' => '30',
                'units' => 'ml',
                'name' => '',
            ],
            '2.5 oz' => [
                'amount' => '2.5',
                'units' => 'oz',
                'name' => '',
            ],
            '1 1/2 oz.' => [
                'amount' => '1 1/2',
                'units' => 'oz',
                'name' => '',
            ],
            '3 lemon wedges' => [
                'amount' => '3',
                'units' => 'wedge',
                'name' => 'lemon',
            ],
            '7,5 ml Comma test' => [
                'amount' => '7,5',
                'units' => 'ml',
                'name' => 'Comma test',
            ],
            '15 ml Vodka (citron)' => [
                'amount' => '15',
                'units' => 'ml',
                'name' => 'Vodka',
                'comment' => 'citron'
            ],
            '15 ml Tequila reposado, preferebly a brand name' => [
                'amount' => '15',
                'units' => 'ml',
                'name' => 'Tequila reposado',
                'comment' => 'preferebly a brand name'
            ],
        ];

        foreach ($testCases as $sourceString => $expectedResult) {
            $result = $parser->parseLine($sourceString);
            $this->assertSame($expectedResult['amount'], $result->amount, sprintf('Wrong amount for "%s"', $sourceString));
            $this->assertSame($expectedResult['units'], $result->units, sprintf('Wrong units for "%s"', $sourceString));
            $this->assertSame($expectedResult['name'], $result->name, sprintf('Wrong name for "%s"', $sourceString));
            $this->assertSame($sourceString, $result->source);
            if (array_key_exists('comment', $expectedResult)) {
                $this->assertSame($expectedResult['comment'], $result->comment, sprintf('Wrong comment for "%s"', $sourceString));
            }
        }
    }

    public function testCustomUnits(): void
    {
        $parser = new Parser();
        $parser->setUnitParser(
            new UnitParser([
                'test' => ['lorem', 'ipsum']
            ])
        );

        $result = $parser->parseLine('15 lorem ingredient names');
        $this->assertSame('test', $result->units);
        $this->assertSame('15', $result->amount);
        $this->assertSame('ingredient names', $result->name);
    }

    public function testStaticCall(): void
    {
        $parsed = Parser::line('30 ml Tequila reposado');

        $this->assertSame('30', $parsed->amount);
    }

    public function testParseAndConvert(): void
    {
        $parser = new Parser();

        $recipeIngredient = $parser->parseLine('1 1/2 oz. mezcal', Units::Ml);
        $this->assertSame(45.0, $recipeIngredient->amount);
        $this->assertSame('ml', $recipeIngredient->units);
        $this->assertSame('mezcal', $recipeIngredient->name);

        $recipeIngredient = $parser->parseLine('1 1/2 oz. mezcal', Units::Oz);
        $this->assertSame(1.5, $recipeIngredient->amount);
        $this->assertSame('oz', $recipeIngredient->units);
        $this->assertSame('mezcal', $recipeIngredient->name);

        $recipeIngredient = $parser->parseLine('15ml mezcal', Units::Oz);
        $this->assertSame(0.5, $recipeIngredient->amount);
        $this->assertSame('oz', $recipeIngredient->units);
        $this->assertSame('mezcal', $recipeIngredient->name);

        $recipeIngredient = $parser->parseLine('15 parts mezcal', Units::Ml);
        $this->assertSame('15', $recipeIngredient->amount);
        $this->assertSame('part', $recipeIngredient->units);
        $this->assertSame('mezcal', $recipeIngredient->name);
    }
}
