<?php

declare(strict_types=1);

namespace Kami\RecipeUtils\Parser;

class CommentParser implements StringParserInterface
{
    public function parse(string $sourceString): array
    {
        $comment = '';

        // Match string between brackets or after comma
        preg_match('/\((.*?)\)|\,(.*)/', $sourceString, $bracketMatcher);
        if (isset($bracketMatcher[0]) && isset($bracketMatcher[1])) {
            // Clean string, remove everything between brackets and after comma
            return [trim($bracketMatcher[0], ",()\n\r\t\v\0 "), trim(str_replace($bracketMatcher[0], '', $sourceString))];
        }

        return [$comment, $sourceString];
    }
}
