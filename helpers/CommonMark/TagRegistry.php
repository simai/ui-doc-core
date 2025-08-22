<?php

    namespace App\Helpers\CommonMark;

    use App\Helpers\Interface\CustomTagInterface;

    final class TagRegistry
    {
        /**
         * @param CustomTagInterface[] $tags
         */
        public static function register(array $tags): CustomTagRegistry
        {
            $registry = new CustomTagRegistry();
            $seen = [];

            foreach ($tags as $tag) {
                if (!$tag instanceof CustomTagInterface) {
                    throw new \InvalidArgumentException('All items must implement CustomTagInterface');
                }

                $type = $tag->type();
                if (isset($seen[$type])) {
                    throw new \RuntimeException("Duplicate custom tag type '{$type}'");
                }
                $seen[$type] = true;

                $registry->register(CustomTagAdapter::toSpec($tag));
            }

            return $registry;
        }
    }
