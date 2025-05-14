<?php


    /** @var $container \Illuminate\Container\Container */
    /** @var $events \TightenCo\Jigsaw\Events\EventBus */

    /**
     * You can run custom code at different stages of the build process by
     * listening to the 'beforeBuild', 'afterCollections', and 'afterBuild' events.
     *
     * For example:
     *
     * $events->beforeBuild(function (Jigsaw $jigsaw) {
     *     // Your code here
     * });
     */


    $events->beforeBuild(function ($jigsaw) {
        $locales = $jigsaw->getConfig('locales');
        $configurator = new \App\Helpers\Configurator($locales);
        $jigsaw->setConfig('configurator', $configurator);
    });

    $events->afterBuild(function ($jigsaw) {
        $index = [];

        foreach ($jigsaw->getConfig('collections') as $collectionName => $config) {
            $collection = $jigsaw->getCollection($collectionName);

            foreach ($collection as $page) {
                $html = $page->getContent();
                $plain = strip_tags($html);
                $headings = [];

                if (preg_match_all('/<h2.*?id="(.*?)".*?>(.*?)<\/h2>/si', $html, $matches, PREG_SET_ORDER)) {
                    foreach ($matches as $match) {
                        $headings[] = [
                            'anchor' => $match[1],
                            'text' => trim(strip_tags($match[2])),
                        ];
                    }
                }
                $title = $page->title ?? '';
                $contentLines = preg_split('/\r\n|\r|\n/', $plain);
                if ($title !== '' && isset($contentLines[0]) && trim($contentLines[0]) === $title) {
                    array_shift($contentLines);
                }


                $cleanedContent = implode("\n", $contentLines);
                $index[$page->language][] = [
                    'title' => $title,
                    'url' => $page->getUrl(),
                    'lang' => $page->language ?? '',
                    'content' => trim($cleanedContent),
                    'headings' => $headings,
                ];
            }
        }

        $dest = $jigsaw->getDestinationPath();

        if (!file_exists($dest)) {
            mkdir($dest, 0755, true);
        }
        foreach ($index as $lang => $page) {
            file_put_contents($dest . "/search-index_{$lang}.json", json_encode($page, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }

    });

