<?php
    namespace App\Helpers\CommonMark;

    use App\Helpers\Interface\CustomTagInterface;

    final class CustomTagAdapter
    {
        /**
         * @param CustomTagInterface $tag
         * @return CustomTagSpec
         */
        public static function toSpec(CustomTagInterface $tag): CustomTagSpec
        {
            $type = $tag->type();
            $open = $tag->openRegex();
            if (!$open) {
                throw new \InvalidArgumentException("CustomTag '{$type}' must define openRegex().");
            }


            $close = $tag->closeRegex() ?: null;

            return new CustomTagSpec(
                type: $type,
                openRegex: $open,
                closeRegex: $close,
                htmlTag: $tag->htmlTag(),
                baseAttrs: $tag->baseAttrs(),
                allowNestingSame: $tag->allowNestingSame(),
                attrsFilter: $tag->attrsFilter(),
                renderer: $tag->renderer()
            );
        }
    }
