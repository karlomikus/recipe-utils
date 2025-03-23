<?php

declare(strict_types=1);

namespace Kami\RecipeUtils;

use Kami\RecipeUtils\UnitConverter\Units;

readonly class RecipeIngredient
{
    public function __construct(
        public string $name,
        public float $amount,
        public string $units,
        public ?string $source = null,
        public ?string $comment = null,
        public ?float $amountMax = null,
    ) {
    }

    public function isEqualTo(RecipeIngredient $compareWith): bool
    {
        return $this->name === $compareWith->name
            && $this->amount === $compareWith->amount
            && $this->units === $compareWith->units;
    }

    /**
     * @param array<Units> $ignoreUnits
     */
    public function convertTo(?Units $convertToUnits = null, array $ignoreUnits = [])
    {
        return Converter::tryConvert(
            $this,
            $convertToUnits,
            $ignoreUnits
        );
    }
}
