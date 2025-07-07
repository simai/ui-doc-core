<?php
    namespace App\Helpers;
    use TightenCo\Jigsaw\Parsers\FrontMatterParser;


    class Parser extends FrontMatterParser
    {

        public function parseMarkdownWithoutFrontMatter($content)

        {
            $content = preg_replace_callback(
                '/!example\s*\n([\s\S]*?)\n!endexample/',
                function ($m) {
                    $inner = trim($m[1]);
                    $innerHtml = parent::parseMarkdownWithoutFrontMatter($inner);
                    return "<div class=\"example overflow-hidden radius-1/2 overflow-x-auto\">{$innerHtml}</div>";
                },
                $content
            );
            return parent::parseMarkdownWithoutFrontMatter($content);
        }
    }
