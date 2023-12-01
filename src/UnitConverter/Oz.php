<?php

declare(strict_types=1);

namespace Kami\RecipeUtils\UnitConverter;

class Oz extends Unit
{
    public function toMl(): Ml
    {
        return new Ml($this->getValue() * 30);
    }

    public function toCl(): Cl
    {
        return new Cl($this->getValue() * 3);
    }

    public function toOz(): Oz
    {
        return new Oz($this->getValue());
    }
}
