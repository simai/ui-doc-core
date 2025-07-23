<?php
    namespace App\Helpers;
    use TightenCo\Jigsaw\Parsers\FrontMatterParser;


    class Parser extends FrontMatterParser
    {

        public function parseMarkdownWithoutFrontMatter($content): string

        {
            foreach (TagRegistry::all() as $tag) {
                $content = preg_replace_callback(
                    $tag->getPattern(),
                    function ($m) use ($tag) {
                        $inner = trim($m[1]);
                        $innerHtml = parent::parseMarkdownWithoutFrontMatter($inner);
                        return $tag->getTemplate($innerHtml);
                    },
                    $content
                );
            }
            return parent::parseMarkdownWithoutFrontMatter($content);
        }
    }
