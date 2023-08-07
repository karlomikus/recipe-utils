<?php

declare(strict_types=1);

namespace Kami\RecipeUtils\Parser;

use Kami\RecipeUtils\RecipeIngredient;

class Parser
{
    public function parse(string $sourceString): RecipeIngredient
    {
        $baseString = $this->normalizeString($sourceString);

        [$amount, $baseString] = (new AmountParser())->parse($baseString);
        [$units, $baseString] = (new UnitParser())->parse($baseString);
        [$name, $baseString] = (new NameParser())->parse($baseString);

        return new RecipeIngredient(
            $name,
            $amount,
            $units,
            $sourceString
        );
    }

    private function normalizeString(string $string): string
    {
        $string = str_replace('*', '', $string);

        if ($encString = iconv('', 'US//TRANSLIT', $string)) {
            $string = trim($encString);
        }

        // Check multiple spaces and replace with a single space
        $string = preg_replace('/[\s]+/', ' ', $string);

        return $string ?? '';
    }
}
