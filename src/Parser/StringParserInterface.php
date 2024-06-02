<?php

declare(strict_types=1);

namespace Kami\RecipeUtils\Parser;

interface StringParserInterface
{
    /**
     * Parse the initial string and return the parsed result and the rest of the string
     *
     * @param string $sourceString Initial string
     * @return array{string, string}
     */
    public function parse(string $sourceString): array;
}
