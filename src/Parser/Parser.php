<?php

declare(strict_types=1);

namespace Kami\RecipeUtils\Parser;

use Kami\RecipeUtils\Converter;
use Kami\RecipeUtils\RecipeIngredient;
use Kami\RecipeUtils\UnitConverter\Units;
use Kami\RecipeUtils\UnitConverter\AmountValue;

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
        'oz' => ['oz.', 'fl-oz', 'oz', 'ounce', 'ounces'],
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
        'cube' => ['cubes', 'cube'],
        'bottle' => ['bottles', 'bottle'],
    ];

    public function __construct()
    {
        $this->amountParser = new AmountParser();
        $this->unitParser = new UnitParser($this->units);
        $this->nameParser = new NameParser();
        $this->commentParser = new CommentParser();
    }

    /**
     * @param array<Units> $ignoreUnits
     */
    public function parseLine(string $sourceString, ?Units $convertToUnits = null, array $ignoreUnits = []): RecipeIngredient
    {
        $baseString = $this->normalizeString($sourceString);

        [$comment, $baseString] = $this->commentParser->parse($baseString);
        [$amount, $baseString] = $this->amountParser->parse($baseString);
        [$units, $baseString] = $this->unitParser->parse($baseString);
        [$name, $baseString] = $this->nameParser->parse($baseString);

        $amountMax = null;
        if (str_contains($amount, '-')) {
            $variableAmount = explode('-', $amount);
            $amountMax = (string) $variableAmount[1];
        }

        $ingredient = new RecipeIngredient(
            $name,
            AmountValue::fromString($amount)->getValue(),
            $units,
            $sourceString,
            $comment,
            $amountMax ? AmountValue::fromString($amountMax)->getValue() : null,
        );

        if ($convertToUnits) {
            return Converter::tryConvert(
                $ingredient,
                $convertToUnits,
                $ignoreUnits
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

    /**
     * @param array<Units> $ignoreUnits
     */
    public static function line(string $sourceString, ?Units $convertToUnits = null, array $ignoreUnits = []): RecipeIngredient
    {
        return (new self())->parseLine($sourceString, $convertToUnits, $ignoreUnits);
    }

    private function normalizeString(string $string): string
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
