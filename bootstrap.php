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

        // Получаем токен последнего коммита
        $url = "https://api.github.com/repos/simai/ui/commits/main";
        $context = stream_context_create([
            'http' => [
                'header' => [
                    'User-Agent: Jigsaw',
                    'Accept: application/vnd.github.v3+json',
                ],
                'timeout' => 3,
            ]
        ]);
        $json = @file_get_contents($url, false, $context);
        if (!$json) {
            return null;
        }
        $data = json_decode($json, true);
        $jigsaw->setConfig('sha', $data['sha'] ?? null);
    });

    $events->afterCollections(function ($jigsaw)  {
        $index = [];
        foreach ($jigsaw->getConfig('collections') as $collectionName => $config) {
            $collection = $jigsaw->getCollection($collectionName);
            foreach ($collection as $page) {
                $html = $page->getContent();
                $plain = strip_tags($html);
                $headings = [];
                $rightMenuHeadings = [];

                if (preg_match_all('/<h2.*?id="(.*?)".*?>(.*?)<\/h2>/si', $html, $matches, PREG_SET_ORDER)) {
                    foreach ($matches as $match) {
                        $headings[] = [
                            'anchor' => $match[1],
                            'text' => trim(strip_tags($match[2])),
                        ];
                    }
                }
                if (preg_match_all('/<(h[1-4])(?: [^>]*id="([^"]*)")?[^>]*>(.*?)<\/\1>/si', $html, $matches, PREG_SET_ORDER)) {
                    foreach ($matches as $match) {
                        $rightMenuHeadings[] = [
                            'level' => $match[1],
                            'anchor' => $match[2],
                            'text' => trim(strip_tags($match[3])),
                        ];
                    }
                }
                $page->set('headings', $rightMenuHeadings);
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
        $jigsaw->setConfig('HEADINGS', $rightMenuHeadings);
        $jigsaw->setConfig('INDEXES', $index);
    });

    $events->afterBuild(function ($jigsaw) {
        $index = $jigsaw->getConfig('INDEXES');
        $dest = $jigsaw->getDestinationPath();

        if (!file_exists($dest)) {
            mkdir($dest, 0755, true);
        }
        foreach ($index as $lang => $page) {

            file_put_contents($dest . "/search-index_{$lang}.json", json_encode($page, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }

    });



