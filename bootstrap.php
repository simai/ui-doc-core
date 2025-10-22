<?php


    /** @var $container \Illuminate\Container\Container */
    /** @var $events \TightenCo\Jigsaw\Events\EventBus */

    use App\Helpers\Configurator;

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



    $configurator = new Configurator($container);
    $events->beforeBuild(function ($jigsaw) use ($container, $configurator) {
        $locales = $jigsaw->getConfig('locales');

        $tempConfig = __DIR__ . '/temp/translations/.config.json';

        if (is_file($tempConfig)) {
            $allLocales = [];
            $tempConfigJson = json_decode(file_get_contents($tempConfig), true) ?: [];
            foreach ($locales as $key => $locale) {
                $allLocales[$key] = $locale;
            }
            foreach ($tempConfigJson as $key => $value) {
                $allLocales[$key] = $value;
            }
            $jigsaw->setConfig('locales', $allLocales);
        }

        $locales = $jigsaw->getConfig('locales');
        $configurator->prepare($locales, $jigsaw);
        $jigsaw->setConfig('configurator', $configurator);


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
    $events->afterCollections(function ($jigsaw) {
        $index = [];
        $paths = [];
        $configurator = $jigsaw->getConfig('configurator');

        foreach ($jigsaw->getConfig('collections') as $collectionName => $config) {
            $collection = $jigsaw->getCollection($collectionName);
            foreach ($collection as  $page) {

                $paths[] = $page->getPath();
                $html = $page->getContent();
                $plain = strip_tags($html);
                $headings = [];
                $rightMenuHeadings = [];

                if (preg_match_all('/<h2.*?id="(.*?)".*?>(.*?)<\/h2>/si', $html, $matches, PREG_SET_ORDER)) {
                    foreach ($matches as $match) {

                        $headings[] = [
                            'anchor' => $match[1],
                            'text' => trim(html_entity_decode(strip_tags($match[2]))),
                        ];
                    }
                }
                if (preg_match_all('/<(h[1-4])(?: [^>]*id="([^"]*)")?[^>]*>(.*?)<\/\1>/si', $html, $matches, PREG_SET_ORDER)) {
                    foreach ($matches as $key => $match) {
                        $text = trim(html_entity_decode(strip_tags($match[3])));
                        $id = $configurator->makeUniqueHeadingId($page->getPath(), $match[1], $key);
                        $issetId = strlen(trim($match[2])) > 0;
                        if ($issetId) {
                            $id = trim($match[2]);
                        }
                        $fingerPrint = $configurator->mkFingerprint($match[3]);
                        $configurator->setFingerprint($id, $fingerPrint);
                        $rightMenuHeadings[$id] = [
                            'level' => $match[1],
                            'id' => $id,
                            'type' => preg_replace('/h/', '', $match[1]),
                            'anchor' => $match[2],
                            'text' => $text,
                        ];
                    }
                }

                $configurator->setHeading($page->getPath(), $rightMenuHeadings);
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
        $configurator->setPaths($paths);
        $jigsaw->setConfig('INDEXES', $index);
    });

    $events->afterBuild(function ($jigsaw) {
        $configurator = $jigsaw->getConfig('configurator');
        $outputPath = $jigsaw->getDestinationPath();
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($outputPath)
        );


        foreach ($iterator as $file) {
            if ($file->isFile() && substr($file->getFilename(), -5) === '.html') {
                $relativePath = str_replace($outputPath, '', preg_replace('#[\\/\\\\]index\.html$#i', '', $file->getPathname()));
                $relativePath = str_replace('\\', '/', $relativePath);
                $html = file_get_contents($file->getPathname());
                $count = 0;
                $html = preg_replace('/<!--.*?-->/s', '', $html);
                $html = preg_replace_callback(
                    '/<(h[1-6])( [^>]*)?>(.*?)<\/\1>/si',
                    function ($match) use (&$count, $relativePath, $html, $configurator) {
                        $fingerPrint = $configurator->mkFingerprint($match[3]);
                        if(!isset($configurator->fingerPrint[$fingerPrint])) {
                            return  $match[0];
                        }
                        $tag = $match[1];
                        $attrs = $match[2] ?? '';
                        if (str_contains($attrs, 'id=')) {
                            return $match[0];
                        }
                        $id = $configurator->makeUniqueHeadingId($relativePath, $tag, $count);
                        $count++;
                        $match[3] = preg_replace(
                            '/(\S+)$/u',
                            '<span class="nowrap">$1<span class="sf-icon">link</span></span>',
                            $match[3]
                        );
                        return "<$tag$attrs id=\"$id\"><a href='#{$id}' onclick='copyAnchor(this)' aria-disabled='false' class='header-anchor'>{$match[3]}</a></$tag>";
                    },
                    $html
                );
                file_put_contents($file->getPathname(), $html);
            }
        }
        $index = $jigsaw->getConfig('INDEXES');
        $dest = $jigsaw->getDestinationPath();
        if (!file_exists($dest)) {
            mkdir($dest, 0755, true);
        }
        foreach ($index as $lang => $page) {

            file_put_contents($dest . "/search-index_{$lang}.json", json_encode($page, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }
    });



