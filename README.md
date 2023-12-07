# Recipe Utilities

Utilities for extracting ingredient data from (mostly cocktail) recipes into structured objects.

## Install

Install via composer

```bash
$ composer require karlomikus/recipe-utils
```

## Parser usage

All parse methods return object of type `Kami\RecipeUtils\RecipeIngredient`.

```php
<?php

declare(strict_types=1);

use Kami\RecipeUtils\Parser\Parser;
use Kami\RecipeUtils\Parser\UnitParser;
use Kami\RecipeUtils\UnitConverter\Units;

$ingredientParser = new Parser();

// Parse a single ingredient line
$ingredient = $ingredientParser->parseLine('30 ml Tequila reposado (preferebly Patron)');
var_dump($ingredient);
// Output:
// $ingredient->amount === '30'
// $ingredient->units === 'ml'
// $ingredient->name === 'Tequila reposado'
// $ingredient->comment === 'preferebly Patron'
// $ingredient->source === '30 ml Tequila reposado'

// Parse a line and convert units if possible
$ingredient = $ingredientParser->parseLine('30 ml Tequila reposado (preferebly Patron)', Units::Oz);
var_dump($ingredient);
// Output:
// $ingredient->amount === 1.0
// $ingredient->units === 'oz'
// $ingredient->name === 'Tequila reposado'
// $ingredient->comment === 'preferebly Patron'
// $ingredient->source === '30 ml Tequila reposado'

// Available via static call
$ingredient = Parser::line('30 ml Tequila reposado (preferebly Patron)');

// Add custom units
$ingredientParser->setUnitParser(
    new UnitParser([
        'test' => ['lorem', 'ipsum']
    ])
);

$ingredient = $ingredientParser->parseLine('15 lorem ingredient names');
// Output:
// $ingredient->units === 'test'
```

## Unit converter usage

Simple unit conversion implemented with enums. Not made for accuracy. Handles mostly cocktail recipe units (ml, oz, cl, dash...). Can handle fractional display amounts (¾, 1 1/2..).

```php
<?php

declare(strict_types=1);

use Kami\RecipeUtils\UnitConverter\Units;
use Kami\RecipeUtils\UnitConverter\Converter;

$ingredientToConvert = new RecipeIngredient(
    name: 'Vodka',
    amount: '1 1/2',
    units: 'oz',
);

$convertedIngredient = Converter::tryConvert($ingredientToConvert, Units::Ml);
var_dump($convertedIngredient);
// Output:
// $ingredient->amount === 45.0
// $ingredient->units === 'ml'
// $ingredient->name === 'Vodka'
```
