<?php

declare(strict_types=1);

namespace Kami\RecipeUtils\UnitConverter;

use Kami\RecipeUtils\AmountValue;

class Cl extends Unit
{
    public function toStandardMlValue(): AmountValue
    {
        return $this->getValue()->multiplyBy(10);
    }
}
