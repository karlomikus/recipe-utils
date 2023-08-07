<?php

declare(strict_types=1);

namespace Kami\RecipeUtilsTests;

use PHPUnit\Framework\TestCase;
use Kami\RecipeUtils\Parser\Parser;
use Kami\RecipeUtils\Parser\UnitParser;

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
        ];

        foreach ($testCases as $sourceString => $expectedResult) {
            $result = $parser->parse($sourceString);
            $this->assertSame($expectedResult['amount'], $result->amount, sprintf('Wrong amount for "%s"', $sourceString));
            $this->assertSame($expectedResult['units'], $result->units, sprintf('Wrong units for "%s"', $sourceString));
            $this->assertSame($expectedResult['name'], $result->name, sprintf('Wrong name for "%s"', $sourceString));
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

        $result = $parser->parse('15 lorem ingredient names');
        $this->assertSame('test', $result->units);
        $this->assertSame('15', $result->amount);
        $this->assertSame('ingredient names', $result->name);
    }
}
