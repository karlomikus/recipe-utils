<?php

declare(strict_types=1);

namespace Kami\RecipeUtils\Parser;

class AmountParser implements StringParserInterface
{
    public function parse(string $sourceString): array
    {
        $amount = ['0', $sourceString];

        // Match variable amounts (ex: 3-6 mint sprigs)
        $matchIntOrFraction = '(?:[1-9][0-9]*|0)(?:\/[1-9][0-9]*)?';
        $hasVariableAmount = preg_match('/^(' . $matchIntOrFraction . '\-' . $matchIntOrFraction . ')|^(' . $matchIntOrFraction . '\s\-\s' . $matchIntOrFraction . ')|^(' . $matchIntOrFraction . '\s(to|or)\s' . $matchIntOrFraction . ')/', $sourceString, $varMatches);
        if ($hasVariableAmount === 1) {
            $amount = $varMatches[0];

            $restOfTheString = $sourceString;
            if ($amount !== '') {
                $restOfTheString = explode($amount, $sourceString)[1];
            }

            $result = [
                str_replace(['to', 'or'], '-', $amount),
                $restOfTheString
            ];

            return array_map('trim', $result);
        }

        // Match specific amounts (ex: 30 ml ingredient, 1 1/2 oz ingredient)
        $matchWholeIntegersAndFractions = '/^(\d+\/\d+)|(\d+\s\d+\/\d+)|(\d+.\d+)|\d+/';
        $hasSpecificAmount = preg_match($matchWholeIntegersAndFractions, $sourceString, $specMatches);
        if ($hasSpecificAmount === 1) {
            $amount = $specMatches[0];

            $restOfTheString = $sourceString;
            if ($amount !== '') {
                $restOfTheString = explode($amount, $sourceString, 2)[1];
            }

            return [trim($amount), trim($restOfTheString)];
        }

        return $amount;
    }
}
