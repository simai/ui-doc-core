<?php
    namespace App\Helpers;

    use DOMDocument;
    use TightenCo\Jigsaw\Parsers\FrontMatterParser;

    class Parser extends FrontMatterParser
    {
        public function parseMarkdownWithoutFrontMatter($content): string
        {

            $codeBlocks = [];
            $content = preg_replace_callback('/```.*?\n.*?```/s', function ($matches) use (&$codeBlocks) {
                $key = 'CODEBLOCK' . count($codeBlocks) . 'PLACEHOLDER';
                $codeBlocks[$key] = $matches[0];
                return $key;
            }, $content);

            $customBlocks = [];
            foreach (TagRegistry::all() as $tag) {
                $content = preg_replace_callback(
                    $tag->getPattern(),
                    function ($m) use (&$customBlocks, $tag) {
                        $key = strtoupper($tag::class) . 'BLOCK' . count($customBlocks) . 'PLACEHOLDER';
                        $customBlocks[$key] = [
                            'tag' => $tag,
                            'content' => trim($m[1])
                        ];
                        return $key;
                    },
                    $content
                );
            }

            $content = str_replace(array_keys($codeBlocks), array_values($codeBlocks), $content);

            $content = parent::parseMarkdownWithoutFrontMatter($content);

            foreach ($customBlocks as $key => $data) {
                $inner = $data['content'];
                $inner = str_replace(array_keys($codeBlocks), array_values($codeBlocks), $inner);
                $innerHtml = parent::parseMarkdownWithoutFrontMatter($inner);
                $content = str_replace($key, $data['tag']->getTemplate($innerHtml), $content);
            }

            return $this::addClassesInlineOnly($content);
        }

        private function addClassesInlineOnly(string $content): string {
            $doc = new DOMDocument();
            libxml_use_internal_errors(true);
            $doc->loadHTML('<!DOCTYPE html><html><head><meta charset="UTF-8"></head><body>'  . $content . '</body></html>');
            libxml_clear_errors();

            // обработка всех элементов
            $all = $doc->getElementsByTagName('*');

            foreach ($all as $el) {
                foreach ($el->childNodes as $child) {
                    if ($child->nodeType === XML_TEXT_NODE &&
                        preg_match('/\(\.([a-zA-Z0-9_.\- ]+)\)/', $child->textContent, $m)
                    ) {
                        $classList = preg_replace('/\s+/', ' ', trim(str_replace('.', ' ', $m[1])));
                        $existing = $el->getAttribute('class');
                        $el->setAttribute('class', trim("$existing $classList"));
                        $child->textContent = str_replace($m[0], '', $child->textContent);
                    }
                }
            }
            return $doc->saveHTML();
}

    }
