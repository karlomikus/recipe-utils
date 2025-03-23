<?php

declare(strict_types=1);

namespace Kami\RecipeUtils\Parser;

use Kami\RecipeUtils\AmountValue;
use Kami\RecipeUtils\RecipeIngredient;
use Kami\RecipeUtils\Parser\Normalizer\StringNormalizer;

class Parser
{
    public function __construct(
        private StringParserInterface $amountParser,
        private StringParserInterface $unitParser,
        private StringParserInterface $nameParser,
        private StringParserInterface $commentParser,
        private StringNormalizer $stringNormalizer,
    ) {
    }

    public function parseLine(string $sourceString): RecipeIngredient
    {
        $baseString = $this->stringNormalizer->normalize($sourceString);

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
            AmountValue::fromString($amount),
            $units,
            $sourceString,
            $comment,
            $amountMax ? AmountValue::fromString($amountMax) : null,
        );

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
}
