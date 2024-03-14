<?php

declare(strict_types=1);

namespace Kami\RecipeUtils\Parser;

use Kami\RecipeUtils\RecipeIngredient;
use Kami\RecipeUtils\UnitConverter\Unit;
use Kami\RecipeUtils\UnitConverter\Units;
use Kami\RecipeUtils\UnitConverter\Converter;

class Parser
{
    private StringParserInterface $amountParser;

    private StringParserInterface $unitParser;

    private StringParserInterface $nameParser;

    private StringParserInterface $commentParser;

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
        'part' => ['part', 'parts'],
        'wedge' => ['wedge', 'wedges'],
    ];

    public function __construct()
    {
        $this->amountParser = new AmountParser();
        $this->unitParser = new UnitParser($this->units);
        $this->nameParser = new NameParser();
        $this->commentParser = new CommentParser();
    }

    public function parseLine(string $sourceString, ?Units $convertToUnits = null): RecipeIngredient
    {
        $baseString = $this->normalizeString($sourceString);

        [$amount, $baseString] = $this->amountParser->parse($baseString);
        [$units, $baseString] = $this->unitParser->parse($baseString);
        [$comment, $baseString] = $this->commentParser->parse($baseString);
        [$name, $baseString] = $this->nameParser->parse($baseString);

        $originalAmount = $amount;
        $amountMax = null;
        if (str_contains($amount, '-')) {
            $variableAmount = explode('-', $amount);
            $amountMax = (string) $variableAmount[1];
        }

        $ingredient = new RecipeIngredient(
            $name,
            Unit::fromString($amount)->getValue(),
            $units,
            $sourceString,
            $originalAmount,
            $comment,
            $amountMax ? Unit::fromString($amountMax)->getValue() : null,
        );

        if ($convertToUnits) {
            return Converter::tryConvert(
                $ingredient,
                $convertToUnits
            );
        }

        return $ingredient;
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

    public function setCommentParser(StringParserInterface $parser): self
    {
        $this->commentParser = $parser;

        return $this;
    }

    /**
     * @return array<string, array<string>>
     */
    public function getUnits(): array
    {
        return $this->units;
    }

    public static function line(string $sourceString, ?Units $convertToUnits = null): RecipeIngredient
    {
        return (new self())->parseLine($sourceString, $convertToUnits);
    }

    private function normalizeString(string $string): string
    {
        $string = html_entity_decode($string);
        $string = str_replace('*', '', $string);
        $string = str_replace(['–', '–', '—'], '-', $string); // Normalize dashes

        if ($encString = iconv('', 'US//TRANSLIT', $string)) {
            $string = trim($encString);
        }

        // Check multiple spaces and replace with a single space
        $string = preg_replace('/[\s]+/', ' ', $string);

        return $string ?? '';
    }
}
