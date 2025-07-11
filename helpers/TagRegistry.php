<?php

namespace App\Helpers;

class TagRegistry
{
    protected static array $tags = [];

    public static function register(CustomTagInterface $tag): void
    {
        self::$tags[] = $tag;
    }

    public static function all(): array
    {
        return self::$tags;
    }
}
