<?php

declare(strict_types=1);

namespace Kami\RecipeUtils\Parser;

class AmountParser implements StringParserInterface
{
    public function parse(string $sourceString): array
    {
        $amount = ['0', $sourceString];

        // Match variable amounts (ex: 3-6 mint sprigs)
        $hasVariableAmount = preg_match('/^(\d+\-\d+)|^(\d+\s\-\s\d+)|^(\d+\s(to)\s\d+)/', $sourceString, $varMatches);
        if ($hasVariableAmount === 1) {
            $amount = $varMatches[0];

            $restOfTheString = $sourceString;
            if ($amount !== '') {
                $restOfTheString = explode($amount, $sourceString)[1];
            }

            $result = [
                str_replace('to', '-', $amount),
                $restOfTheString
            ];

            return array_filter(array_map('trim', $result));
        }

        // Match specific amounts (ex: 30 ml ingredient, 1 1/2 oz ingredient)
        $hasSpecificAmount = preg_match('/^(\d+\/\d+)|(\d+\s\d+\/\d+)|(\d+.\d+)|\d+/', $sourceString, $specMatches);
        if ($hasSpecificAmount === 1) {
            $amount = $specMatches[0];

            $restOfTheString = $sourceString;
            if ($amount !== '') {
                $restOfTheString = explode($amount, $sourceString)[1];
            }

            return [trim($amount), trim($restOfTheString)];
        }

        return $amount;
    }
}
