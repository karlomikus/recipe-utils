<?php

declare(strict_types=1);

namespace Kami\RecipeUtils\UnitConverter;

enum Units: string
{
    case Cl = 'cl';
    case Ml = 'ml';
    case Oz = 'oz';
    case Dash = 'dash';
    case Shot = 'shot';
    case Part = 'part';

    public function getClassName(): string
    {
        return match ($this) {
            Units::Cl => Cl::class,
            Units::Ml => Ml::class,
            Units::Oz => Oz::class,
            Units::Dash => Dash::class,
            Units::Shot => Shot::class,
            Units::Part => Part::class,
        };
    }
}
