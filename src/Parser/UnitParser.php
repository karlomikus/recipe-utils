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
        foreach ($this->units as $unit => $alts) {
            foreach ($alts as $matchUnit) {
                // Match the whole word
                // Note: This will still have problems matching ingredients if they
                // have multiple matches in the string, so it's difficult to guess order of matching
                $matchWholeWordRegex = '/\b' . $matchUnit . '\b/i';
                if (preg_match($matchWholeWordRegex, $sourceString) === 1) {
                    return [$unit, trim(preg_replace($matchWholeWordRegex, '', $sourceString, 1) ?? '', " \n\r\t\v\x00\.")];
                }
            }
        }

        return ['', $sourceString];
    }
}
