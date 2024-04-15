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
}
