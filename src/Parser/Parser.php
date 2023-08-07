<?php

declare(strict_types=1);

namespace Kami\RecipeUtils\Parser;

use Kami\RecipeUtils\RecipeIngredient;

class Parser
{
    private StringParserInterface $amountParser;

    private StringParserInterface $unitParser;

    private StringParserInterface $nameParser;

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

    public function __construct()
    {
        $this->amountParser = new AmountParser();
        $this->unitParser = new UnitParser($this->units);
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

    /**
     * @return array<string, array<string>>
     */
    public function getUnits(): array
    {
        return $this->units;
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
