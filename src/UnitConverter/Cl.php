<?php

declare(strict_types=1);

namespace Kami\RecipeUtils\UnitConverter;

class Cl extends Unit
{
    public function toStandardMlValue(): AmountValue
    {
        return new AmountValue($this->getValue() * 10);
    }
}
