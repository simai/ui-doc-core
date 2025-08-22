<?php

    namespace App\Helpers\CommonMark;

    final class CustomTagSpec
    {
        public string $type;
        public string $openRegex;
        public string $closeRegex;
        public string $htmlTag = 'div';
        public array $baseAttrs = [];
        public bool $allowNestingSame = true;
        public ?\Closure $attrsFilter = null;

        public ?\Closure $renderer = null;

        /**
         * @param string $type
         * @param string $openRegex
         * @param string $closeRegex
         * @param string $htmlTag
         * @param array $baseAttrs
         * @param bool $allowNestingSame
         * @param callable|null $attrsFilter
         * @param callable|null $renderer
         */
        public function __construct(
            string    $type,
            string    $openRegex,
            string    $closeRegex,
            string    $htmlTag = 'div',
            array     $baseAttrs = [],
            bool      $allowNestingSame = true,
            ?callable $attrsFilter = null,
            ?callable $renderer = null
        )
        {
            $this->type = $type;
            $this->openRegex = $openRegex;
            $this->closeRegex = $closeRegex;
            $this->htmlTag = $htmlTag;
            $this->baseAttrs = $baseAttrs;
            $this->allowNestingSame = $allowNestingSame;


            $this->attrsFilter = $attrsFilter ? $attrsFilter(...) : null;
            $this->renderer = $renderer ? $renderer(...) : null;
        }
    }

