<?php

    namespace App\Helpers\CommonMark;

    use App\Helpers\Interface\CustomTagInterface;

    abstract class BaseTag implements CustomTagInterface
    {
        /**
         * @return string
         */
        abstract public function type(): string;

        /**
         * @return string
         */
        public function openRegex(): string { return '/^\s*!'.preg_quote($this->type(), '/').'(?:\s+(?<attrs>.+))?$/u'; }

        /**
         * @return string
         */
        public function closeRegex(): string { return '/^\s*!end'.preg_quote($this->type(), '/').'\s*$/u'; }

        /**
         * @return string
         */
        public function htmlTag(): string { return 'div'; }

        /**
         * @return array
         */
        public function baseAttrs(): array { return []; }

        /**
         * @return bool
         */
        public function allowNestingSame(): bool { return true; }

        /**
         * @return callable|null
         */
        public function attrsFilter(): ?callable { return null; }

        /**
         * @return callable|null
         */
        public function renderer(): ?callable { return null; }
    }
