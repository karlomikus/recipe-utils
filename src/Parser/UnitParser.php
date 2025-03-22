<?php

declare(strict_types=1);

namespace Kami\RecipeUtils\Parser;

class UnitParser implements StringParserInterface
{
    /**
     * @param array<string, array<string>> $units
     */
    public function __construct(private readonly array $units = [])
    {
    }

    public function parse(string $sourceString): array
    {
        // Tokenize the input string into words
        $words = preg_split('/\s+/', trim($sourceString));

        foreach ($this->units as $unit => $alts) {
            foreach ($alts as $matchUnit) {
                // Loop through words to check for an exact match
                foreach ($words as $index => $word) {
                    if (strcasecmp($word, $matchUnit) === 0) { // Case-insensitive match
                        unset($words[$index]); // Remove matched unit
                        return [$unit, trim(implode(' ', $words), " \n\r\t\v\x00.")];
                    }
                }
            }
        }

        return ['', $sourceString]; // No unit found
    }
}
