<?php

declare(strict_types=1);

namespace Kami\RecipeUtils\Parser;

use Kami\RecipeUtils\RecipeIngredient;

class Parser
{
    private StringParserInterface $amountParser;

    private StringParserInterface $unitParser;

    private StringParserInterface $nameParser;

    public function __construct()
    {
        $this->amountParser = new AmountParser();
        $this->unitParser = new UnitParser();
        $this->nameParser = new NameParser();
    }

    public function parse(string $sourceString): RecipeIngredient
    {
        $baseString = $this->normalizeString($sourceString);

        [$amount, $baseString] = $this->amountParser->parse($baseString);
        [$units, $baseString] = $this->unitParser->parse($baseString);
        [$name, $baseString] = $this->nameParser->parse($baseString);

        return new RecipeIngredient(
            $name,
            $amount,
            $units,
            $sourceString
        );
    }

    public function setAmountParser(StringParserInterface $parser): self
    {
        $this->amountParser = $parser;

        return $this;
    }

    public function setUnitParser(StringParserInterface $parser): self
    {
        $this->unitParser = $parser;

        return $this;
    }

    public function setNameParser(StringParserInterface $parser): self
    {
        $this->nameParser = $parser;

        return $this;
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
