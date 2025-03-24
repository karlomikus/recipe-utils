# Recipe Utilities

![GitHub Actions Workflow Status](https://img.shields.io/github/actions/workflow/status/karlomikus/recipe-utils/code.yml)
![Packagist Version](https://img.shields.io/packagist/v/karlomikus/recipe-utils)
![GitHub License](https://img.shields.io/github/license/karlomikus/recipe-utils)

Utilities for extracting ingredient data from (mostly cocktail) recipes into structured objects.

## Install

Install via composer

```bash
$ composer require karlomikus/recipe-utils
```

## Parser usage

All parse methods return object of type `Kami\RecipeUtils\RecipeIngredient`.

Supported formats:
- Variable amounts and fractional amounts: "1 - 2", "0.5 to 1", "½ or 2 1/5"
- Comments are anything after comma or anything in brackets: "12 ml ingredient, preferebly something specific"
- Basic units mostly used in cocktails: ml, cl, oz, sprigs, dashes, etc...

```php
<?php

declare(strict_types=1);

use Kami\RecipeUtils\ParserFactory;
use Kami\RecipeUtils\Parser\UnitParser;
use Kami\RecipeUtils\UnitConverter\Units;

// Create parser with sensible defaults
$ingredientParser = ParserFactory::make();

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
$ingredient = $ingredientParser->parseLine('1/2 - 1 ounce lime juice (freshly squeezed)')->convertTo(Units::Ml);
var_dump($ingredient);
// Output:
// $ingredient->amount === 15.0
// $ingredient->amount_max === 30.0
// $ingredient->units === 'ml'
// $ingredient->name === 'lime juice'
// $ingredient->comment === 'freshly squeezed'
```

## Unit converter usage

Simple unit conversion implemented with enums. Not made for accuracy. Handles mostly cocktail recipe units (ml, oz, cl, dash...). Can handle fractional display amounts (¾, 1 1/2..).

```php
<?php

declare(strict_types=1);

use Kami\RecipeUtils\Converter;
use Kami\RecipeUtils\UnitConverter\Oz;
use Kami\RecipeUtils\UnitConverter\Units;
use Kami\RecipeUtils\UnitConverter\AmountValue;

// Via existing ingredient object
$ingredientToConvert = new RecipeIngredient(
    name: 'Vodka',
    amount: '1 1/2',
    units: 'oz',
)->convertTo(Units::Ml);

$convertedIngredient = Converter::tryConvert($ingredientToConvert, Units::Ml);
var_dump($convertedIngredient);
// Output:
// $ingredient->amount === 45.0
// $ingredient->units === 'ml'
// $ingredient->name === 'Vodka'

// Via specific units
$amountValue = AmountValue::fromString('1 1/2');
var_dump((new Oz($amountValue))->toMl()->getValue());
// Output:
// float: 45.0
```
