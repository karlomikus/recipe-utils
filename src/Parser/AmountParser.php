<?php

declare(strict_types=1);

namespace Kami\RecipeUtils\Parser;

class AmountParser implements StringParserInterface
{
    public function parse(string $sourceString): array
    {
        $amount = ['0', $sourceString];

        // Possible variation delimiters
        $stringVariableMatchers = ['-', 'to', 'or'];

        // Match variable amounts (ex: 3-6 mint sprigs)
        $matchVariableAmounts = '/(\d+\s*\d*\/?\d*)\s*(?:' . implode('|', $stringVariableMatchers) . ')\s*(\d+\s*\d*\/?\d*)/i';

        $hasVariableAmount = preg_match($matchVariableAmounts, $sourceString, $varMatches);
        if ($hasVariableAmount === 1) {
            $amount = $varMatches[0];

            $restOfTheString = $sourceString;
            if ($amount !== '') {
                $restOfTheString = explode($amount, $sourceString)[1];
            }

            $result = [
                str_replace($stringVariableMatchers, '-', $amount),
                $restOfTheString
            ];

            return array_map(trim(...), $result);
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
