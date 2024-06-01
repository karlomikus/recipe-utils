<?php

declare(strict_types=1);

namespace Kami\RecipeUtils\Parser;

class CommentParser implements StringParserInterface
{
    public function parse(string $sourceString): array
    {
        $comment = '';

        // Match string between brackets
        preg_match('/\((.*?)\)/', $sourceString, $bracketMatcher);
        if (isset($bracketMatcher[1])) {
            return [trim($bracketMatcher[1]), trim(str_replace($bracketMatcher[0], '', $sourceString))];
        }

        // Match string after comma
        preg_match('/\,(.*)/', $sourceString, $commaMatcher);
        if (isset($commaMatcher[1])) {
            return [trim($commaMatcher[1]), trim(str_replace($commaMatcher[0], '', $sourceString))];
        }

        return [$comment, $sourceString];
    }
}
