<?php

declare(strict_types=1);

namespace Kami\RecipeUtils\Parser;

class UnitParser implements StringParserInterface
{
    /**
     * @var array<string, array<string>>
     */
    private array $units = [
        'oz' => ['oz.', 'ounce', 'fl-oz', 'oz', 'ounces'],
        'ml' => ['ml', 'ml.', 'milliliter', 'milliliters'],
        'cl' => ['cl', 'cl.', 'centiliter', 'centiliters'],
        'dash' => ['dashes', 'dash'],
        'sprigs' => ['sprig', 'sprigs'],
        'leaves' => ['leaves', 'leaf'],
        'whole' => ['whole'],
        'drops' => ['drop', 'drops'],
        'barspoon' => ['barspoon', 'teaspoon', 'tsp', 'tsp.', 'tspn', 't', 't.', 'teaspoon', 'teaspoons', 'tablespoons', 'tablespoon'],
        'slice' => ['slice', 'sliced', 'slices'],
        'cup' => ['c', 'c.', 'cup', 'cups'],
        'pint' => ['pt', 'pts', 'pt.', 'pint', 'pints'],
        'splash' => ['splash', 'splashes'],
        'pinch' => ['pinches', 'pinch'],
        'topup' => ['topup'],
    ];

    public function parse(string $sourceString): array
    {
        foreach ($this->units as $unit => $alts) {
            foreach ($alts as $matchUnit) {
                // Match the whole word
                if (preg_match('/\b' . $matchUnit . '\b/i', $sourceString) === 1) {
                    return [$unit, trim(preg_replace('/\b' . $matchUnit . '\b/i', '', $sourceString) ?? '', " \n\r\t\v\x00\.")];
                }
            }
        }

        return ['', $sourceString];
    }
}
