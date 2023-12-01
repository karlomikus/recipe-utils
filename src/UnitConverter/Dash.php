<?php

declare(strict_types=1);

namespace Kami\RecipeUtils\UnitConverter;

class Dash extends Unit
{
    // public function toMl(): Ml
    // {
    //     return new Ml($this->getValue() * 0.3125);
    // }

    public function toDash(): Dash
    {
        return new Dash($this->getValue());
    }
}
