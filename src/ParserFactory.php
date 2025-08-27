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
     * @param array<string, non-empty-array<string>> $units
     */
    public static function make(array $units = []): Parser
    {
        $units = array_merge($units, DefaultUnits::get());

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
