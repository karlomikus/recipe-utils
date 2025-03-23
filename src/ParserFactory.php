<?php

declare(strict_types=1);

namespace Kami\RecipeUtils;

use Kami\RecipeUtils\Parser\Parser;
use Kami\RecipeUtils\Parser\NameParser;
use Kami\RecipeUtils\Parser\UnitParser;
use Kami\RecipeUtils\Parser\AmountParser;
use Kami\RecipeUtils\Parser\CommentParser;
use Kami\RecipeUtils\Parser\Normalizer\StringNormalizer;

final class ParserFactory
{
    /**
     * Creates a new Parser instance with sensible defaults.
     *
     * @param array<string, array<string>> $units
     */
    public static function make(array $units = []): Parser
    {
        $units = array_merge($units, [
            'oz' => ['oz.', 'fl-oz', 'oz', 'ounce', 'ounces'],
            'ml' => ['ml', 'ml.', 'milliliter', 'milliliters'],
            'cl' => ['cl', 'cl.', 'centiliter', 'centiliters'],
            'dash' => ['dashes', 'dash', 'ds'],
            'sprigs' => ['sprig', 'sprigs'],
            'leaves' => ['leaves', 'leaf'],
            'whole' => ['whole'],
            'drops' => ['drop', 'drops'],
            'barspoon' => ['barspoon', 'teaspoon', 'bsp', 'tsp', 'tsp.', 'tspn', 't', 't.', 'teaspoon', 'teaspoons', 'tablespoons', 'tablespoon'],
            'slice' => ['slice', 'sliced', 'slices'],
            'cup' => ['c', 'c.', 'cup', 'cups'],
            'pint' => ['pt', 'pts', 'pt.', 'pint', 'pints'],
            'splash' => ['a splash', 'splash', 'splashes'],
            'pinch' => ['pinches', 'pinch'],
            'topup' => ['topup'],
            'part' => ['part', 'parts'],
            'wedge' => ['wedge', 'wedges'],
            'cube' => ['cubes', 'cube'],
            'bottle' => ['bottles', 'bottle'],
            'can' => ['cans', 'can'],
            'bag' => ['bags', 'bag'],
            'shot' => ['shots', 'shot'],
        ]);

        $amountParser = new AmountParser();
        $unitParser = new UnitParser($units);
        $nameParser = new NameParser();
        $commentParser = new CommentParser();
        $stringNormalizer = new StringNormalizer();

        return new Parser(
            amountParser: $amountParser,
            unitParser: $unitParser,
            nameParser: $nameParser,
            commentParser: $commentParser,
            stringNormalizer: $stringNormalizer,
        );
    }
}
