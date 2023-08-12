<?php

declare(strict_types=1);

namespace Kami\RecipeUtils;

class RecipeIngredient
{
    public function __construct(
        public readonly string $name,
        public readonly string|float|int $amount,
        public readonly string $units,
        public readonly string $source,
    ) {
    }
}
