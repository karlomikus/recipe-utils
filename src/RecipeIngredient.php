<?php

declare(strict_types=1);

namespace Kami\RecipeUtils;

use Kami\RecipeUtils\UnitConverter\Units;

use Stringable;

readonly class RecipeIngredient implements Stringable
{
    public function __construct(
        public string $name,
        public AmountValue $amount,
        public string $units,
        public ?string $source = null,
        public ?string $comment = null,
        public ?AmountValue $amountMax = null,
    ) {
    }

    public function isEqualTo(RecipeIngredient $compareWith): bool
    {
        return $this->name === $compareWith->name
            && $this->amount === $compareWith->amount
            && $this->units === $compareWith->units;
    }

    public function getUnitsAsEnum(): ?Units
    {
        return Units::tryFrom($this->units);
    }

    /**
     * @param array<Units> $ignoreUnits
     */
    public function convertTo(Units $convertToUnits, array $ignoreUnits = []): self
    {
        if ($this->getUnitsAsEnum() === null || in_array($this->getUnitsAsEnum(), $ignoreUnits)) {
            return $this;
        }

        return new self(
            $this->name,
            Converter::convertAmount($this->amount, $this->getUnitsAsEnum(), $convertToUnits),
            $convertToUnits->value,
            $this->source,
            $this->comment,
            $this->amountMax ? Converter::convertAmount($this->amountMax, $this->getUnitsAsEnum(), $convertToUnits) : null,
        );
    }

    public function __toString(): string
    {
        $amount = (string) $this->amount;

        if ($this->amountMax) {
            $amount = sprintf('%s - %s', $this->amount, $this->amountMax);
        }

        return sprintf('%s %s %s', $amount, $this->units, $this->name);
    }
}
