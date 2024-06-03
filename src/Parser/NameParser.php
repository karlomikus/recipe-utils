<?php

declare(strict_types=1);

namespace Kami\RecipeUtils\Parser;

class NameParser implements StringParserInterface
{
    public function parse(string $sourceString): array
    {
        $sourceString = trim($sourceString);

        return [$sourceString, ''];
    }
}
