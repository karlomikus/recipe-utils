# Recipe Utilities

Utilities for extracting normalized data from recipes

## Usage

```php
<?php

declare(strict_types=1);

use Kami\RecipeUtils\Parser\Parser;

$ingredientParser = new Parser();

$ingredient = $ingredientParser->parse('30 ml Tequila reposado');
var_dump($ingredient);

/**
 * $ingredient->amount === '30'
 * $ingredient->units === 'ml'
 * $ingredient->name === 'Tequila reposado'
 */
```
