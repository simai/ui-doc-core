<?php

namespace App\Helpers\CustomTags;

use App\Helpers\Interface\CustomTagInterface;

class ExampleTag implements CustomTagInterface
{
    public function getPattern(): string
    {
        return '/!example\s*\n([\s\S]*?)\n!endexample/';
    }

    public function getTemplate(string $template): string
    {
        return "<div class=\"example overflow-hidden radius-1/2 overflow-x-auto\">{$template}</div>";
    }

    public function getType(): string
    {
        // TODO: Implement getType() method.
    }

    public function getOpeningPattern(): ?string
    {
        return '/^!example\s*$/m';
    }

    public function getClosingPattern(): ?string
    {
        return '/^!endexample\s*$/m';
    }
}
