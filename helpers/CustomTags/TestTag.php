<?php

    namespace App\Helpers\CustomTags;

    use App\Helpers\Interface\CustomTagInterface;

    class TestTag implements CustomTagInterface {
        public function getPattern(): string
        {
            return '/!\s*([^\n!]+?)\s*!/u';
        }

        public function getTemplate(string $template): string
        {
            return "<div class=\"test\">{$template}</div>";
        }

        public function getType(): string
        {
            // TODO: Implement getType() method.
        }

        public function getOpeningPattern(): ?string
        {
            // TODO: Implement getOpeningPattern() method.
        }

        public function getClosingPattern(): ?string
        {
            // TODO: Implement getClosingPattern() method.
        }
    }
