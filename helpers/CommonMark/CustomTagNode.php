<?php
    namespace App\Helpers\CommonMark;

    use League\CommonMark\Node\Block\AbstractBlock;

    class CustomTagNode extends AbstractBlock
    {
        private array $lines = [];
        private ?string $translatedContent = null;

        public function __construct(
            private string $tagType
        ) {
            parent::__construct();
        }

        public function getTagType(): string
        {
            return $this->tagType;
        }


        public function setTranslatedContent(string $text): void
        {
            $this->translatedContent = $text;
        }

        public function getLiteral(): string
        {
            return $this->translatedContent ?? '';
        }

        public function isContainer(): bool
        {
            return true;
        }
    }

