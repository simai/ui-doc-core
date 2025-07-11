<?php
    namespace App\Helpers;
    use TightenCo\Jigsaw\Parsers\FrontMatterParser;


    class Parser extends FrontMatterParser
    {

        public function parseMarkdownWithoutFrontMatter($content): string

        {
            foreach (TagRegistry::all() as $tag) {
                $content = $tag->parse($content, );
            }
            return parent::parseMarkdownWithoutFrontMatter($content);
        }
    }
