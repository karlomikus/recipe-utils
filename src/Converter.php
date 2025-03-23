<?php

declare(strict_types=1);

namespace Kami\RecipeUtils;

use Kami\RecipeUtils\AmountValue;
use Kami\RecipeUtils\RecipeIngredient;
use Kami\RecipeUtils\UnitConverter\Units;

class Converter
{
    /**
     * @param array<Units> $ignoreUnits Units in this array won't get converted
     */
    public static function tryConvert(RecipeIngredient $recipeIngredient, Units $to, array $ignoreUnits = []): RecipeIngredient
    {
        $from = Units::tryFrom($recipeIngredient->units);
        if ($from === null || in_array($from, $ignoreUnits)) {
            return $recipeIngredient;
        }

        $converterClass = $from->getClassName();
        $fromValue = $recipeIngredient->amount;
        $fromUnit = new $converterClass($fromValue);
        $method = 'to' . $to->name;

        if (!method_exists($fromUnit, $method)) {
            return $recipeIngredient;
        }

        // TODO: Convert max amount
        return new RecipeIngredient(
            $recipeIngredient->name,
            AmountValue::from($fromUnit->{$method}()->getValue()),
            $to->value,
            $recipeIngredient->source,
            $recipeIngredient->comment,
            $recipeIngredient->amountMax
        );
    }

    public static function fromTo(float $amount, Units $from, Units $to): float
    {
        $fromValue = AmountValue::from($amount);
        $fromClass = $from->getClassName();

        return (new $fromClass($fromValue))->{'to' . $to->name}()->getValue();
    }
}
