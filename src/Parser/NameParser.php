<?php

declare(strict_types=1);

namespace Kami\RecipeUtils\Parser;

class NameParser implements StringParserInterface
{
    public function parse(string $sourceString): array
    {
        // Remove everything between brackets, and after comma
        $sourceString = trim(preg_replace('/\((.*?)\)|\,(.*)/', '', $sourceString) ?? '');

        return [$sourceString, ''];
    }
}
