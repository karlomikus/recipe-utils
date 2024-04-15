<?php

declare(strict_types=1);

namespace Kami\RecipeUtils\UnitConverter;

class Ml extends Unit
{
    public function toStandardMlValue(): AmountValue
    {
        return new AmountValue($this->getValue());
    }
}
