<?php

declare(strict_types=1);

namespace Kami\RecipeUtilsTests;

use PHPUnit\Framework\TestCase;
use Kami\RecipeUtils\Parser\Parser;
use Kami\RecipeUtils\Parser\UnitParser;
use Kami\RecipeUtils\UnitConverter\Units;
use PHPUnit\Framework\Attributes\DataProvider;
use Kami\RecipeUtils\Parser\StringParserInterface;

final class ParserTest extends TestCase
{
    /**
     * @param array<mixed> $expectedResult
     */
    #[DataProvider('provideIngredients')]
    public function testParse(string $sourceString, array $expectedResult): void
    {
        $parser = new Parser();

        $result = $parser->parseLine($sourceString);
        $this->assertSame($expectedResult['amount'], $result->amount, sprintf('Wrong amount for "%s"', $sourceString));
        $this->assertSame($expectedResult['units'], $result->units, sprintf('Wrong units for "%s"', $sourceString));
        $this->assertSame($expectedResult['name'], $result->name, sprintf('Wrong name for "%s"', $sourceString));
        $this->assertSame($sourceString, $result->source);
        if (array_key_exists('comment', $expectedResult)) {
            $this->assertSame($expectedResult['comment'], $result->comment, sprintf('Wrong comment for "%s"', $sourceString));
        }
        if (array_key_exists('amount_max', $expectedResult)) {
            $this->assertSame($expectedResult['amount_max'], $result->amountMax);
        }
    }

    public function testParseWithCustomUnits(): void
    {
        $parser = new Parser();
        $parser->setUnitParser(
            new UnitParser([
                'test' => ['lorem', 'ipsum']
            ])
        );

        $result = $parser->parseLine('15 lorem ingredient names');
        $this->assertSame('test', $result->units);
        $this->assertSame(15.0, $result->amount);
        $this->assertSame('ingredient names', $result->name);
    }

    public function testStaticCall(): void
    {
        $parsed = Parser::line('30 ml Tequila reposado');

        $this->assertSame(30.0, $parsed->amount);
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

        $recipeIngredient = $parser->parseLine('1.5 parts mezcal', Units::Ml);
        $this->assertSame(45.0, $recipeIngredient->amount);
        $this->assertSame('ml', $recipeIngredient->units);
        $this->assertSame('mezcal', $recipeIngredient->name);
    }

    public function testCustomParsers(): void
    {
        $parser = new Parser();
        $parser->setAmountParser(new class() implements StringParserInterface {
            public function parse(string $sourceString): array
            {
                return ['55', '2'];
            }
        });
        $parser->setCommentParser(new class() implements StringParserInterface {
            public function parse(string $sourceString): array
            {
                return ['comment', '4'];
            }
        });
        $parser->setNameParser(new class() implements StringParserInterface {
            public function parse(string $sourceString): array
            {
                return ['name', '6'];
            }
        });
        $parser->setUnitParser(new class() implements StringParserInterface {
            public function parse(string $sourceString): array
            {
                return ['unit', '8'];
            }
        });
        $ing = $parser->parseLine('TEST');

        $this->assertSame(55.0, $ing->amount);
        $this->assertSame('comment', $ing->comment);
        $this->assertSame('name', $ing->name);
        $this->assertSame('unit', $ing->units);
    }

    public function testGetUnitsFromParser(): void
    {
        $parser = new Parser();

        $this->assertNotEmpty($parser->getUnits());
    }

    /**
     * @return array<mixed>
     */
    public static function provideIngredients(): array
    {
        return [
            '30 ml Tequila reposado' => [
                '30 ml Tequila reposado',
                [
                    'amount' => 30.0,
                    'units' => 'ml',
                    'name' => 'Tequila reposado'
                ],
            ],
            '4 ounces (½ cup) ginger beer' => [
                '4 ounces (½ cup) ginger beer',
                [
                    'amount' => 4.0,
                    'units' => 'oz',
                    'name' => 'ginger beer'
                ],
            ],
            '0.5 oz John D. Taylor’s Velvet Falernum' => [
                '0.5 oz John D. Taylor’s Velvet Falernum',
                [
                    'amount' => 0.5,
                    'units' => 'oz',
                    'name' => 'John D. Taylor\'s Velvet Falernum'
                ]
            ],
            '1 1/2 oz. mezcal (Talbert uses Del Maguey Vida)' => [
                '1 1/2 oz. mezcal (Talbert uses Del Maguey Vida)',
                [
                    'amount' => 1.5,
                    'units' => 'oz',
                    'name' => 'mezcal',
                    'comment' => 'Talbert uses Del Maguey Vida'
                ]
            ],
            '1 sliced strawberry' => [
                '1 sliced strawberry',
                [
                    'amount' => 1.0,
                    'units' => 'slice',
                    'name' => 'strawberry'
                ]
            ],
            '2-3 mint sprigs' => [
                '2-3 mint sprigs',
                [
                    'amount' => 2.0,
                    'amount_max' => 3.0,
                    'units' => 'sprigs',
                    'name' => 'mint'
                ]
            ],
            '2 to 3 mint sprigs' => [
                '2 to 3 mint sprigs',
                [
                    'amount' => 2.0,
                    'amount_max' => 3.0,
                    'units' => 'sprigs',
                    'name' => 'mint'
                ]
            ],
            '2 or 3 mint sprigs' => [
                '2 or 3 mint sprigs',
                [
                    'amount' => 2.0,
                    'amount_max' => 3.0,
                    'units' => 'sprigs',
                    'name' => 'mint'
                ]
            ],
            '2 oz. spiced rum' => [
                '2 oz. spiced rum',
                [
                    'amount' => 2.0,
                    'units' => 'oz',
                    'name' => 'spiced rum'
                ]
            ],
            'Maraschino cherries' => [
                'Maraschino cherries',
                [
                    'amount' => 0.0,
                    'units' => '',
                    'name' => 'Maraschino cherries'
                ]
            ],
            'barspoon Pedro Ximenez' => [
                'barspoon Pedro Ximenez',
                [
                    'amount' => 0.0,
                    'units' => 'barspoon',
                    'name' => 'Pedro Ximenez'
                ]
            ],
            '2-3 large basil leaves' => [
                '2-3 large basil leaves',
                [
                    'amount' => 2.0,
                    'amount_max' => 3.0,
                    'units' => 'leaves',
                    'name' => 'large basil'
                ]
            ],
            '2 Dashes Angostura Bitters' => [
                '2 Dashes Angostura Bitters',
                [
                    'amount' => 2.0,
                    'units' => 'dash',
                    'name' => 'Angostura Bitters'
                ]
            ],
            '30 ml' => [
                '30 ml',
                [
                    'amount' => 30.0,
                    'units' => 'ml',
                    'name' => ''
                ]
            ],
            '2.5 oz' => [
                '2.5 oz',
                [
                    'amount' => 2.5,
                    'units' => 'oz',
                    'name' => ''
                ]
            ],
            '1 1/2 oz.' => [
                '1 1/2 oz.',
                [
                    'amount' => 1.5,
                    'units' => 'oz',
                    'name' => ''
                ]
            ],
            '3 lemon wedges' => [
                '3 lemon wedges',
                [
                    'amount' => 3.0,
                    'units' => 'wedge',
                    'name' => 'lemon'
                ]
            ],
            '7,5 ml Comma test' => [
                '7,5 ml Comma test',
                [
                    'amount' => 7.5,
                    'units' => 'ml',
                    'name' => 'Comma test'
                ]
            ],
            '15 ml Vodka (citron)' => [
                '15 ml Vodka (citron)',
                [
                    'amount' => 15.0,
                    'units' => 'ml',
                    'name' => 'Vodka',
                    'comment' => 'citron'
                ]
            ],
            '15 ml Tequila reposado, preferebly a brand name' => [
                '15 ml Tequila reposado, preferebly a brand name',
                [
                    'amount' => 15.0,
                    'units' => 'ml',
                    'name' => 'Tequila reposado',
                    'comment' => 'preferebly a brand name'
                ]
            ],
            '3/4 to 1 ounce eggnog (see Editor\'s Note)' => [
                '3/4 to 1 ounce eggnog (see Editor\'s Note)',
                [
                    'amount' => 0.75,
                    'amount_max' => 1.0,
                    'units' => 'oz',
                    'name' => 'eggnog',
                    'comment' => 'see Editor\'s Note'
                ]
            ],
            '2 ounces pineapple juice, frozen into two 1-ounce cubes' => [
                '2 ounces pineapple juice, frozen into two 1-ounce cubes',
                [
                    'amount' => 2.0,
                    'units' => 'oz',
                    'name' => 'pineapple juice',
                    'comment' => 'frozen into two 1-ounce cubes'
                ]
            ],
            '1 ounce Coco mix (3:1, Coco Lopez: coconut milk) ' => [
                '1 ounce Coco mix (3:1, Coco Lopez: coconut milk) ',
                [
                    'amount' => 1.0,
                    'units' => 'oz',
                    'name' => 'Coco mix',
                    'comment' => '3:1, Coco Lopez: coconut milk'
                ]
            ],
            '1 ounce pomegranate juice, frozen into one 1-ounce cube' => [
                '1 ounce pomegranate juice, frozen into one 1-ounce cube',
                [
                    'amount' => 1.0,
                    'units' => 'oz',
                    'name' => 'pomegranate juice',
                    'comment' => 'frozen into one 1-ounce cube'
                ]
            ],
            '1 “hard ice” cube (one 1-ounce cube of frozen water)' => [
                '1 “hard ice” cube (one 1-ounce cube of frozen water)',
                [
                    'amount' => 1.0,
                    'units' => 'cube',
                    'name' => '"hard ice"',
                    'comment' => 'one 1-ounce cube of frozen water'
                ]
            ],
            '1/2 - 3/4 ounce lime juice' => [
                '1/2 - 3/4 ounce lime juice',
                [
                    'amount' => 0.5,
                    'amount_max' => 0.75,
                    'units' => 'oz',
                    'name' => 'lime juice',
                ]
            ],
            '1 shot espresso' => [
                '1 shot espresso',
                [
                    'amount' => 1.0,
                    'units' => 'shot',
                    'name' => 'espresso',
                ]
            ],
            '½ to 1½ teaspoons hot pepper sauce, or to taste' => [
                '½ to 1½ teaspoons hot pepper sauce, or to taste',
                [
                    'amount' => 0.5,
                    'amount_max' => 1.5,
                    'units' => 'barspoon',
                    'name' => 'hot pepper sauce',
                    'comment' => 'or to taste',
                ]
            ],
            'a splash of guaraná juice or soda' => [
                'a splash of guaraná juice or soda',
                [
                    'amount' => 0.0,
                    'units' => 'splash',
                    'name' => 'a of guarana juice or soda',
                ]
            ],
        ];
    }
}
