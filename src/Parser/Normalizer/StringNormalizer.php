<?php

declare(strict_types=1);

namespace Kami\RecipeUtils\Parser\Normalizer;

final class StringNormalizer
{
    public function normalize(string $string): string
    {
        $string = html_entity_decode($string);
        $string = str_replace('*', '', $string);
        $string = str_replace(['–', '–', '—'], '-', $string); // Normalize dashes

        if ($encString = iconv('', 'US//TRANSLIT', $string)) {
            $string = trim($encString);
        }

        // TODO: Check for missing closing brackets
        // Check multiple spaces and replace with a single space
        $string = preg_replace('/[\s]+/', ' ', $string) ?? '';
        // Convert all decimals with comma to decimals with dot, helps with comment parsing
        $string = preg_replace('/(?<=\d),(?=\d)/', '.', $string);

        return $string ?? '';
    }
}
