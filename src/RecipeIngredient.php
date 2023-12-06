<?php

declare(strict_types=1);

namespace Kami\RecipeUtils;

readonly class RecipeIngredient
{
    public function __construct(
        public string $name,
        public string|float|int $amount,
        public string $units,
        public string $source,
        public ?string $comment = null
    ) {
    }
}
