<?php

declare(strict_types=1);

namespace Kami\RecipeUtils;

use Kami\RecipeUtils\AmountValue;
use Kami\RecipeUtils\UnitConverter\Units;

final class Converter
{
    public static function convertAmount(AmountValue $amount, Units $from, Units $to): AmountValue
    {
        $fromClass = $from->getClassNameForConversion();

        return (new $fromClass($amount))->{'to' . $to->name}()->getValue();
    }
}
