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

    public function testStringable(): void
    {
        $this->assertSame('30 ml London dry gin', (string) new RecipeIngredient('London dry gin', new AmountValue(30.0), 'ml'));
        $this->assertSame('0.5 - 2 oz London dry gin', (string) new RecipeIngredient('London dry gin', new AmountValue(0.5), 'oz', 'source', 'comment', new AmountValue(2.0)));
    }
}
