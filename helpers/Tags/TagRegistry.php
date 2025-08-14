<?php

    namespace App\Helpers\Tags;

    use App\Helpers\Interface\CustomTagInterface;

    class TagRegistry
    {
        protected static array $tags = [];

        public static function register(array|CustomTagInterface $tags): void
        {
            if (is_array($tags)) {
                foreach ($tags as $tag) {
                    self::$tags[] = $tag;
                }
            } else {
                self::$tags[] = $tags;
            }
        }

        public static function all(): array
        {
            return self::$tags;
        }
    }
