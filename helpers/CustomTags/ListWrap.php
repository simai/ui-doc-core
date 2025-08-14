<?php

namespace App\Helpers\CustomTags;

use App\Helpers\Interface\CustomTagInterface;

class ListWrap implements CustomTagInterface
{
    public function getPattern(): string
    {
        return '/!links\s*\n([\s\S]*?)\n!endlinks/';
    }

    public function getTemplate(string $template): string
    {
        return "<div class=\"links\">{$template}</div>";
    }

    public function getType(): string
    {
        // TODO: Implement getType() method.
    }

    public function getOpeningPattern(): ?string
    {
        return '/^!links\s*$/m';
    }

    public function getClosingPattern(): ?string
    {
        return '/^!endlinks\s*$/m';
    }
}
