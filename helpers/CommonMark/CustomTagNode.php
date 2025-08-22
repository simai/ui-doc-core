<?php
    namespace App\Helpers\CommonMark;

    use League\CommonMark\Node\Block\AbstractBlock;

    final class CustomTagNode extends AbstractBlock
    {
        public function __construct(
            private string $type,
            private array $attrs = [],
            private array $meta = [],
        ) {
            parent::__construct();
        }

        /**
         * @return string
         */
        public function getType(): string
        {
            return $this->type;
        }

        /**
         * @return bool
         */
        public function isContainer(): bool
        {
            return true;
        }

        /**
         * @return array
         */
        public function getAttrs(): array
        {
            return $this->attrs;
        }

        /**
         * @param array $attrs
         * @return void
         */
        public function setAttrs(array $attrs): void
        {
            $this->attrs = $attrs;
        }

        /**
         * @param string $class
         * @return void
         */
        public function addClass(string $class): void
        {
            $cur = $this->attrs['class'] ?? '';
            $list = array_filter(array_unique(array_merge(
                $cur ? preg_split('/\s+/', $cur) : [],
                preg_split('/\s+/', trim($class))
            )));
            if ($list) {
                $this->attrs['class'] = implode(' ', $list);
            }
        }

        /**
         * @return array
         */
        public function getMeta(): array
        {
            return $this->meta;
        }
    }
