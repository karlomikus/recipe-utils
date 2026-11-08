<?php

declare(strict_types=1);

namespace Kami\RecipeUtils;

final class DefaultUnits
{
    /**
     * Get a default list of unit mapping used in parser.
     *
     * @return array<string, non-empty-array<string>>
     */
    public static function get(): array
    {
        return [
            'oz' => ['oz.', 'fl-oz', 'oz', 'ounce', 'ounces'],
            'ml' => ['ml', 'ml.', 'milliliter', 'milliliters'],
            'cl' => ['cl', 'cl.', 'centiliter', 'centiliters'],
            'g' => ['g', 'gram', 'grams'],
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
        ];
    }
}
