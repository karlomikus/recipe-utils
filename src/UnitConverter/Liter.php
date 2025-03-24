<?php

declare(strict_types=1);

namespace Kami\RecipeUtils\UnitConverter;

use Kami\RecipeUtils\AmountValue;

class Liter extends Unit
{
    public function toStandardMlValue(): AmountValue
    {
        return new AmountValue($this->getValue()->getValue() * 1000);
    }
}
