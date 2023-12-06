<?php

declare(strict_types=1);

namespace Kami\RecipeUtils;

readonly class RecipeIngredient
{
    public function __construct(
        public string $name,
        public float $amount,
        public string $units,
        public string $source,
        public ?string $originalAmount = null,
        public ?string $comment = null,
        public ?float $amountMax = null,
    ) {
    }
}
