<?php

declare(strict_types=1);

namespace Kami\RecipeUtils\UnitConverter;

abstract class Unit implements UnitInterface
{
    public function __construct(private readonly AmountValue $value)
    {
    }

    public function getValue(): float
    {
        return round($this->value->getValue(), 2);
    }

    public function toMl(): Ml
    {
        return new Ml($this->toStandardMlValue());
    }

    public function toCl(): Cl
    {
        return new Cl(AmountValue::from($this->toStandardMlValue()->getValue() / 10));
    }

    public function toOz(): Oz
    {
        return new Oz(AmountValue::from($this->toStandardMlValue()->getValue() / 30));
    }

    public function toDash(): Dash
    {
        return new Dash(AmountValue::from($this->toStandardMlValue()->getValue() / 0.3125));
    }

    public function toShot(): Shot
    {
        return new Shot($this->toStandardMlValue());
    }

    public function toPart(): Part
    {
        return new Part($this->toStandardMlValue());
    }

    abstract public function toStandardMlValue(): AmountValue;
}
