<?php

declare(strict_types=1);

namespace Kami\RecipeUtilsTests;

use PHPUnit\Framework\TestCase;
use Kami\RecipeUtils\AmountValue;
use Kami\RecipeUtils\RecipeIngredient;

final class RecipeIngredientTest extends TestCase
{
    public function testComparison(): void
    {
        $r1 = new RecipeIngredient('Test', new AmountValue(10.0), 'ml');
        $r2 = new RecipeIngredient('Test', new AmountValue(15.0), 'ml');

        $this->assertFalse($r1->isEqualTo($r2));
    }
}
