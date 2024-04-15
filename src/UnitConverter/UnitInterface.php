<?php

declare(strict_types=1);

namespace Kami\RecipeUtils\UnitConverter;

interface UnitInterface
{
    public function getValue(): float;
    public function toMl(): Ml;
    public function toCl(): Cl;
    public function toOz(): Oz;
    public function toDash(): Dash;
    public function toShot(): Shot;
    public function toPart(): Part;
}