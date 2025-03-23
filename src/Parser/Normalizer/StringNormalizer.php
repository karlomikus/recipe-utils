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

        // Check for missing closing brackets
        $bracketPairs = [
            '(' => ')',
            '{' => '}',
            '[' => ']',
        ];
        foreach ($bracketPairs as $open => $close) {
            $openCount = substr_count($string, $open);
            $closeCount = substr_count($string, $close);
            if ($openCount > $closeCount) {
                $string .= str_repeat($close, $openCount - $closeCount);
            }
        }

        // Check multiple spaces and replace with a single space
        $string = preg_replace('/[\s]+/', ' ', $string) ?? '';
        // Convert all decimals with comma to decimals with dot, helps with comment parsing
        $string = preg_replace('/(?<=\d),(?=\d)/', '.', $string);

        return $string ?? '';
    }
}
