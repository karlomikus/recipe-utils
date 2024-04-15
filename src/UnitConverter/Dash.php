<?php

declare(strict_types=1);

namespace Kami\RecipeUtils\UnitConverter;

class Dash extends Unit
{
    public function toStandardMlValue(): AmountValue
    {
        return new AmountValue($this->getValue() * 0.3125);
    }
}
