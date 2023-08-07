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
                if (preg_match('/\b' . $matchUnit . '\b/i', $sourceString) === 1) {
                    return [$unit, trim(preg_replace('/\b' . $matchUnit . '\b/i', '', $sourceString) ?? '', " \n\r\t\v\x00\.")];
                }
            }
        }

        return ['', $sourceString];
    }
}
