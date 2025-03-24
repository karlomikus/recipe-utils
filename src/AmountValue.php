<?php

declare(strict_types=1);

namespace Kami\RecipeUtils;

use Stringable;

final class AmountValue implements Stringable
{
    public function __construct(private readonly float $value)
    {
    }

    /**
     * Try to parse string representation of number to a float
     * Accounts only for the most usual cocktail unit representation
     *
     * @param string $value
     * @return self
     */
    public static function fromString(string $value): self
    {
        // Remove unicode fraction display
        if ($encString = iconv('', 'US//TRANSLIT', $value)) {
            $value = trim($encString);
        }

        // String is not fractional display (1/2 or 1 2/3), just cast to float
        if (!str_contains($value, '/') && !str_contains($value, ' ')) {
            $value = str_replace(',', '.', $value);

            return new self((float) $value);
        }

        // Solve fractional display
        $solveFraction = function (string $string): float {
            $amountSplit = explode('/', $string);

            return (int) $amountSplit[0] / (int) ($amountSplit[1] ?? 1);
        };

        $amount = 0.0;

        if ($splString = preg_split("/[\s]+/", $value, flags: PREG_SPLIT_NO_EMPTY)) {
            // Match whole number with fractional (example: "1 1/2")
            if (count($splString) === 2) {
                $amount = (float) $splString[0];
                $amount += $solveFraction($splString[1]);
            } else {
                $amount = $solveFraction($splString[0]);
            }
        }

        return new self((float) $amount);
    }

    public static function from(float $value): self
    {
        return new self($value);
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function multiplyBy(float $number): self
    {
        return new self($this->value * $number);
    }

    public function divideBy(float $number): self
    {
        return new self($this->value / $number);
    }

    public function __toString(): string
    {
        return (string) round($this->value, 2);
    }
}
