<?php

    namespace App\Helpers;

    use App\Helpers\CommonMark\CustomTagRegistry;
    use App\Helpers\CommonMark\TagRegistry;
    use App\Helpers\Handlers\CollectionDataLoader;
    use App\Helpers\Handlers\CustomCollectionItemHandler;
    use App\Helpers\Handlers\CustomIgnoredHandler;
    use App\Helpers\Handlers\CustomOutputPathResolver;
    use App\Helpers\Handlers\MultipleHandler;
    use App\Helpers\Interface\CustomTagInterface;
    use FilesystemIterator;
    use Illuminate\Support\Str;
    use RecursiveDirectoryIterator;
    use RecursiveIteratorIterator;
    use ReflectionException;
    use TightenCo\Jigsaw\CollectionItemHandlers\BladeCollectionItemHandler;
    use TightenCo\Jigsaw\CollectionItemHandlers\MarkdownCollectionItemHandler;
    use TightenCo\Jigsaw\Container;
    use TightenCo\Jigsaw\Handlers\BladeHandler;
    use TightenCo\Jigsaw\Handlers\CollectionItemHandler;
    use TightenCo\Jigsaw\Handlers\DefaultHandler;
    use TightenCo\Jigsaw\Handlers\IgnoredHandler;
    use TightenCo\Jigsaw\Handlers\MarkdownHandler;
    use TightenCo\Jigsaw\Handlers\PaginatedPageHandler;
    use TightenCo\Jigsaw\Loaders\CollectionDataLoader as JigsawLoader;
    use TightenCo\Jigsaw\Parsers\FrontMatterParser;
    use TightenCo\Jigsaw\PathResolvers\CollectionPathResolver;
    use TightenCo\Jigsaw\SiteBuilder;

    class Configurator
    {

        public array $locales;
        public array $paths = [];
        public array $settings;
        public array $fingerPrint = [];

        public bool $useCategory = false;

        public array $translations = [];
        public string $distPath = '';
        public array $headings;
        public array $menu;

        public array $flattenMenu;

        public bool $hasIndexPage = false;

        public string $indexPage = '';

        private MultipleHandler $multipleHandler;

        public array $topMenu = [];
        public array $realFlatten = [];
        public string $locale = 'en';
        public string $docsDir = 'source/docs/';
        private Container $container;

        /**
         * @param Container $container
         */
        public function __construct(Container $container)
        {
            $this->container = $container;
            $this->docsDir = 'source/' . $_ENV['DOCS_DIR'] . '/';
            $this->bind();
            $this->extractImages();
        }


        public function prepare($locales, $jigsaw): void
        {
            $this->distPath = $jigsaw->getDestinationPath();
            $this->useCategory = $jigsaw->getConfig('category');
            $this->locale = $jigsaw->getConfig('defaultLocale');
            $this->locales = array_keys($locales->toArray());
            $this->makeSettings();

            if ($this->useCategory) {
                $this->multipleHandler = new MultipleHandler();
                $this->makeMultipleStructure();
            } else {
                $this->makeSingleStructure();
            }
            $this->makeLocales();
        }

        /**
         * @param $array
         * @param $path
         * @param $value
         * @param $locale
         * @return void
         */
        private function array_set_deep(&$array, $path, $value, $locale): void
        {
            $segments = explode('/', $path);
            if ($segments[0] === '') {
                $segments[0] = $locale;
            } else {
                $segments = explode('/', $locale . '/' . $path);
            }
            $current = &$array;
            foreach ($segments as $segment) {
                if (!isset($current['pages'][$segment])) {
                    $current['pages'][$segment] = [];
                }
                $current = &$current['pages'][$segment];
            }
            $dir = $this->docsDir . '/' . $locale;
            if (is_dir($dir . '/' . $path)) {
                if (is_file($dir . '/' . $path . '/index.md') || is_file($dir . '/' . $path . '/' . $path . '.md')) {
                    $value['has_index'] = true;
                } else {
                    $value['has_index'] = false;
                }
            };
            $current['current'] = $value;
        }

        /**
         * @param $locale
         * @param $segments
         * @return array
         */
        public function generateBreadCrumbs($locale, $segments): array
        {

            $items = [];
            if (empty($this->realFlatten) || !isset($this->realFlatten[$locale])) return $items;

            $path = '';
            foreach ($segments as $segment) {
                $path .= '/' . $segment;
                foreach ($this->realFlatten[$locale] as $value) {
                    if ($value['path']) {
                        $link = $value['path'];
                    } else {
                        $link = $value['navPath'];
                    }
                    if ($link === $path) {
                        $items[] = $value;
                    }
                }
            }
            return $items;
        }

        /**
         * @return void
         */
        public function makeLocales(): void
        {
            foreach ($this->locales as $locale) {
                $locales = [];
                $file = $this->docsDir . '/' . $locale . '/.lang.php';
                if (is_file($file)) {
                    $content = include $file;
                    $this->translations[$locale] = $content;
                }
            }
        }

        /**
         * @param string $locale
         * @return array|null
         */
        public function getJsTranslations(string $locale): array|null
        {
            if (!empty($this->translations[$locale])) {
                return $this->translations[$locale];
            }
            return null;
        }


        /**
         * @param array $pages
         * @param array $menu
         * @return void
         */
        private function sortPagesRecursively(array &$pages, array $menu): void
        {
            $sortedPages = [];


            foreach ($menu as $key => $_) {
                if (isset($pages[$key])) {

                    if (isset($pages[$key]['pages']) && isset($pages[$key]['current']['menu'])) {

                        $this->sortPagesRecursively($pages[$key]['pages'], $pages[$key]['current']['menu']);
                    }
                    $sortedPages[$key] = $pages[$key];
                }
            }
            foreach ($pages as $key => $value) {
                if (!isset($sortedPages[$key])) {
                    $sortedPages[$key] = $value;
                }
            }

            $pages = $sortedPages;
        }

        /**
         * @param $items
         * @return array
         */
        private function sortPages($items): array
        {
            foreach ($items['pages'] as &$item) {
                $current = $item;
                if (!isset($current['pages']) || !isset($current['current'])) continue;
                $this->sortPagesRecursively($item['pages'], $item['current']['menu']);
            }


            return $items;

        }

        /**
         * @return void
         */
        public function makeSingleStructure(): void
        {
            foreach ($this->locales as $locale) {
                $pages = $this->makeFlatten($this->settings[$locale], $locale);
                $filteredPages = array_filter($pages['flat'], function ($item) {
                    return $item['path'] !== null;
                });
                $this->flattenMenu[$locale] = array_values($filteredPages);
                $this->realFlatten[$locale] = $pages['flat'];

                $this->menu[$locale] = $this->buildMenuTree($this->settings[$locale] ?? [], '', $locale);
            }
        }

        /**
         * @return void
         */
        public function makeMultipleStructure(): void
        {
            foreach ($this->locales as $locale) {
                foreach ($this->settings[$locale] as $item) {
                    $this->hasIndexPage = $item['current']['has_index'];
                    if ($this->hasIndexPage) {
                        $this->indexPage = '';
                    }
                    if (isset($item['current']['menu'])) {
                        foreach ($item['current']['menu'] as $key => $title) {
                            $isLink = $this->isLink($key);
                            $path = '/' . $locale . '/' . $key;
                            if (!$this->hasIndexPage && !$isLink) {
                                $this->hasIndexPage = true;
                                $this->indexPage = $key;
                            }
                            $this->topMenu[$locale][$key] = [
                                'path' => $isLink ? $key : $path,
                                'isLink' => $isLink,
                                'title' => $title,
                            ];
                            if (!$isLink && isset($item['pages'][$key])) {

                                $menu = $this->buildMenuTree([
                                    $key => $item['pages'][$key]
                                ] ?? [], '', $locale);


                                $this->topMenu[$locale][$key]['children'] = $item['pages'][$key];
                                $pages = $this->makeFlatten([
                                    $key => $item['pages'][$key]
                                ], $locale);

                                $this->multipleHandler->setFlatten($locale, $key, $pages);
                                $this->multipleHandler->setMenu($locale, $key, $menu);
                            }
                        }
                    }
                }
            }

        }

        /**
         * @return void
         */
        public function makeSettings(): void
        {
            foreach ($this->locales as $locale) {
                $settings = [];
                $dir = $this->docsDir . $locale;
                if (is_dir($dir)) {
                    foreach (new RecursiveIteratorIterator(
                                 new RecursiveDirectoryIterator($dir)
                             ) as $file) {
                        if ($file->isFile() && $file->getFilename() === '.settings.php') {
                            $relativePath = str_replace($dir, '', dirname($file->getPathname()));
                            $relativePath = ltrim(str_replace('\\', '/', $relativePath), '/');
                            $this->array_set_deep($settings, $relativePath, include $file->getPathname(), $locale);
                        }
                    }
                    if (!file_exists($this->distPath)) {
                        mkdir($this->distPath, 0755, true);
                    }

                    if (empty($settings)) {
                        return;
                    }


                    $settings = $this->sortPages($settings);
                    $this->settings[$locale] = $settings['pages'] ?? [];

                }
            }
        }

        /**
         * @param array $items
         * @param string $locale
         * @return array[]
         */
        public function makeFlatten(array $items, string $locale): array
        {
            $pages = [
                'flat' => [],
            ];
            $this->makeMenu($items, $pages, '', $locale);
            return $pages;
        }

        /**
         * @param string $locale
         * @return array
         */
        public function getMenu(string $locale, array $path = []): array
        {
            if ($this->useCategory) {
                if (count($path) < 2) return [];
                [$locale, $key] = $path;

                return $this->multipleHandler->getMenuByCategory($locale, $key);
            } else {
                return $this->menu[$locale] ?? [];
            }
        }

        /**
         * @param string $locale
         * @return array
         */
        public function getTopMenu(string $locale): array
        {
            return $this->topMenu[$locale] ?? [];
        }

        /**
         * @param array $items
         * @param string $prefix
         * @param string $locale
         * @return array
         */
        function buildMenuTree(array $items, string $prefix = '', string $locale = 'en'): array
        {
            $tree = [];

            foreach ($items as $slug => $item) {
                if ($this->useCategory && $prefix === '') {
                    $slug = '/' . $locale . '/' . $slug;
                }
                $itemsSet = false;
                $title = $item['current']['title'] ?? null;
                $hasSub = !empty($item['pages']);
                $menu = $item['current']['menu'] ?? [];
                $isLink = $this->isLink($slug);
                $currentPath = $prefix ? $prefix . '/' . $slug : $slug;

                if ($prefix === '' && $slug === $locale) {
                    $fullPath = '/' . $locale;
                } else {
                    $fullPath = '/' . trim($currentPath, '/');
                }
                $tree[$fullPath] = [
                    'title' => $title,
                    'path' => $isLink ? $slug : $fullPath,
                    'isLink' => $isLink,
                    'children' => [],
                ];

                if (is_array($menu)) {
                    foreach ($menu as $menuKey => $menuLabel) {
                        $isLink = $this->isLink($menuKey);
                        if (isset($item['pages'][$menuKey])) {
                            if ($hasSub) {
                                $itemsSet = true;
                                $tree[$fullPath]['children'] += $this->buildMenuTree($item['pages'], $currentPath, $locale);
                            }
                            continue;
                        };

                        $menuPath = $fullPath . '/' . $menuKey;

                        $tree[$fullPath]['children'][$menuPath] = [
                            'title' => $menuLabel,
                            'path' => $isLink ? $menuKey : $menuPath,
                            'isLink' => $isLink,
                            'children' => [],
                        ];
                    }
                }
                if (!$itemsSet && $hasSub) {
                    $tree[$fullPath]['children'] += $this->buildMenuTree($item['pages'], $currentPath, $locale);
                }
            }

            return $tree;
        }

        /**
         * @param string $string
         * @return bool
         */
        public function isLink(string $string): bool
        {
            return Str::startsWith($string, ['http', 'https']);
        }

        /**
         * @param array $items
         * @param array $pages
         * @param string $prefix
         * @param string $locale
         * @return void
         */
        public function makeMenu(array $items, array &$pages, string $prefix = '', string $locale = 'ru'): void
        {
            foreach ($items as $key => $value) {
                $hasChildren = isset($value['pages']) && is_array($value['pages']);
                $path = trim($prefix . '/' . $key, '/');
                $setItem = false;
                $fullPath = trim($path, '/');
                $isLink = $this->isLink($key);
                if (isset($value['current']) && $value['current']['has_index']) {
                    $finalPath = str_ends_with($fullPath, $path) ? $fullPath : trim($fullPath . '/' . $path, '/');
                    $menuPath = '/' . $finalPath;
                    $pages['flat'][] = [
                        'key' => $path,
                        'path' => $isLink ? $key : $menuPath,
                        'label' => $value['current']['title'],
                    ];
                    $setItem = true;
                }
                if (!$setItem && isset($value['current']['title'])) {
                    $finalPath = str_ends_with($fullPath, $path) ? $fullPath : trim($fullPath . '/' . $path, '/');
                    $menuPath = '/' . $finalPath;
                    $pages['flat'][] = [
                        'key' => $path,
                        'path' => null,
                        'navPath' => $isLink ? $key : $menuPath,
                        'label' => $value['current']['title'],
                    ];
                }
                if (isset($value['current']['menu']) && is_array($value['current']['menu'])) {
                    foreach ($value['current']['menu'] as $menuKey => $menuLabel) {
                        if (isset($value['pages'][$menuKey]) || $this->isLink($menuKey)) continue;
                        $finalPath = str_ends_with($fullPath, $menuKey) ? $fullPath : trim($fullPath . '/' . $menuKey, '/');
                        $menuPath = '/' . $finalPath;
                        $pages['flat'][] = [
                            'key' => $menuKey,
                            'path' => $menuPath,
                            'label' => $menuLabel,
                        ];
                    }
                }


                if ($hasChildren) {
                    $this->makeMenu($value['pages'], $pages, $path, $locale);
                }
            }
        }

        /**
         * @param string $html
         * @return string
         */
        public function mkFingerprint(string $html): string
        {
            $t = trim(html_entity_decode(strip_tags($html)));
            $t = preg_replace('/\s+/u', ' ', mb_strtolower($t));
            return md5($t);
        }

        public function setFingerprint($id, string $fingerprint): void
        {
            $this->fingerPrint[$fingerprint] = $id;
        }


        /**
         * @param $relativePath
         * @param $level
         * @param $index
         * @return string
         */
        public function makeUniqueHeadingId($relativePath, $level, $index): string
        {
            $base = $relativePath . '-' . $level . '-' . $index;
            return 'h-' . substr(md5($base), 0, 12);
        }

        /**
         * @param $path
         * @param $headings
         * @return void
         */
        public function setHeading($path, $headings): void
        {
            $this->headings[$path] = $headings;
        }


        /**
         * @param array $items
         * @param array $flat
         * @return array
         */
        public function flattenNav(array $items, array &$flat): array
        {

            foreach ($items as $key => $value) {
                if (is_array($value) && $key === 'menu') {
                    $flat = array_merge($flat, $value);
                } else if (is_array($value)) {
                    $this->flattenNav($value, $flat);
                }
            }

            return $flat;
        }

        /**
         * @param string $path
         * @param string $locale
         * @return array
         */
        public function getPrevAndNext(string $path, string $locale): array
        {
            if (!isset($this->flattenMenu[$locale])) return [];
            $flattenNav = $this->flattenMenu[$locale];
            $returnArr = [];
            $needly = 0;
            foreach ($flattenNav as $key => $value) {
                if (!$value['path']) {
                    continue;
                }
                if ($value['path'] === $path) {
                    $needly = $key;
                    break;
                }

            }

            if ($needly === 0) {
                $returnArr['next'] = $flattenNav[1];
            } else {
                $returnArr['prev'] = $flattenNav[$needly - 1];
                if (isset($flattenNav[$needly + 1])) {
                    $returnArr['next'] = $flattenNav[$needly + 1];
                }
            }
            return $returnArr;
        }

        /**
         * @param string $locale
         * @return void
         */
        public function setLocale(string $locale): void
        {
            $this->locale = $locale;
        }

        /**
         * @param array $paths
         * @return void
         */
        public function setPaths(array $paths): void
        {
            $this->paths = array_merge($paths, $this->paths);
        }

        /**
         * @param $text
         * @param $locale
         * @return string
         */
        public function getTranslate($text, $locale): string
        {
            return $this->translations[$locale][$text] ?? '';

        }

        /**
         * @param $locale
         * @return array
         */
        public function getItems($locale): array
        {
            return $this->settings[$locale] ?? [];
        }

        /**
         * @param string $mimeExt
         * @return string
         */
        function extFromMime(string $mimeExt): string
        {
            $map = ['jpeg' => 'jpg', 'png' => 'png', 'svg+xml' => 'svg'];
            return $map[$mimeExt] ?? $mimeExt;
        }

        /**
         * @param string $source
         * @param string $b64
         * @param string $ext
         * @return string
         */
        function saveB64AndReturnRel(string $source, string $b64, string $ext): string
        {
            $b64 = preg_replace('/\s+/', '', $b64);
            $bytes = base64_decode($b64, true);
            if ($bytes === false) return 'assets/build/img/b64/invalid.' . $ext;
            $hash = substr(sha1($bytes), 0, 16);
            $rel = "assets/build/img/b64/{$hash}.{$ext}";
            $dst = $source . '/' . $rel;
            @mkdir(dirname($dst), 0775, true);
            if (!file_exists($dst)) file_put_contents($dst, $bytes);
            return $rel;
        }

        /**
         * @param bool $dryRun
         * @return void
         */
        function extractImages(bool $dryRun = false): void
        {
            @ini_set('pcre.backtrack_limit', '10000000');
            @ini_set('pcre.recursion_limit', '100000');

            $source = __DIR__ . '/source/' . $_ENV['DOCS_DIR'];
            $scanDirs = glob("{$source}/*", GLOB_ONLYDIR) ?: [];

            $reInlineShortcut = '~!\[([^\]]*)\]\(\s*b64:([A-Za-z0-9+/=\s]+)(?:,?\s*ext=([a-z0-9]+))?\s*\)~i';
            $reInlineDataUri = '~!\[([^\]]*)\]\(\s*data:image/([a-z0-9.+-]+);base64,([A-Za-z0-9+/=\s]+)\s*\)~i';
            $reRefDataUri = '~^\[([^\]]+)\]:\s*<?\s*data:image/([a-z0-9.+-]+);base64,([A-Za-z0-9+/=\s]+)>?\s*$~im';

            $changedFiles = 0;
            $inlineHits = 0;
            $refHits = 0;

            foreach ($scanDirs as $dir) {
                $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(
                    $dir,
                    FilesystemIterator::SKIP_DOTS | FilesystemIterator::CURRENT_AS_FILEINFO
                ));


                foreach ($it as $file) {
                    if (!$file->isFile() || strtolower($file->getExtension()) !== 'md') continue;

                    $path = $file->getPathname();
                    $md = file_get_contents($path);
                    if ($md === false || $md === '') continue;
                    $orig = $md;

                    $md = preg_replace_callback($reInlineShortcut, function ($m) use ($source, &$inlineHits) {
                        $alt = $m[1];
                        $b64 = $m[2];
                        $ext = $m[3] ?? 'png';
                        $rel = $this->saveB64AndReturnRel($source, $b64, $ext);
                        $inlineHits++;
                        return '![' . $alt . '](/' . $rel . ')';
                    }, $md);

                    $md = preg_replace_callback($reInlineDataUri, function ($m) use ($source, &$inlineHits) {
                        $alt = $m[1];
                        $ext = $this->extFromMime($m[2]);
                        $b64 = $m[3];
                        $rel = $this->saveB64AndReturnRel($source, $b64, $ext);
                        $inlineHits++;
                        return '![' . $alt . '](/' . $rel . ')';
                    }, $md);

                    $md = preg_replace_callback($reRefDataUri, function ($m) use ($source, &$refHits) {
                        $label = $m[1];
                        $ext = $this->extFromMime($m[2]);
                        $b64 = $m[3];
                        $rel = $this->saveB64AndReturnRel($source, $b64, $ext);
                        $refHits++;
                        return '[' . $label . ']: /' . $rel;
                    }, $md);

                    if ($md !== $orig) {
                        $changedFiles++;
                        if ($dryRun) {
                            echo "[dry-run] would update: {$path}\n";
                        } else {
                            file_put_contents($path, $md);
                            echo "Updated: {$path}\n";
                        }
                    }
                }
            }

            echo "Done. Files changed: {$changedFiles}; inline saved: {$inlineHits}; ref saved: {$refHits}\n";
        }


        /**
         * @return void
         */
        public function bind(): void
        {
            try {
                $this->container->forgetInstance(SiteBuilder::class);
                $this->container->bind(SiteBuilder::class, function ($app) {
                    return new SiteBuilder(
                        $app['files'],
                        $app->cachePath(),
                        $app['outputPathResolver'],
                        $app['consoleOutput'],
                        [
                            $app->make(CollectionItemHandler::class),
                            $app->make(CustomIgnoredHandler::class),
                            $app->make(PaginatedPageHandler::class),
                            $app->make(MarkdownHandler::class),
                            $app->make(BladeHandler::class),
                            $app->make(DefaultHandler::class),
                        ]
                    );
                });
                $this->container->bind('outputPathResolver', function () {
                    return new CustomOutputPathResolver();
                });
                $this->container->bind(IgnoredHandler::class, CustomIgnoredHandler::class);

                $this->container->bind(JigsawLoader::class, function (Container $app) {

                    return new CollectionDataLoader(
                        $app['files'], $app['consoleOutput'], $app[CollectionPathResolver::class], [
                            $app[MarkdownCollectionItemHandler::class],
                            $app[BladeCollectionItemHandler::class],
                        ]
                    );
                });
                $this->container->bind(CollectionItemHandler::class, function ($c) {
                    $config = $c['config'];
                    $handlers = [
                        $c->make(MarkdownHandler::class),
                        $c->make(BladeHandler::class),
                    ];

                    return new CustomCollectionItemHandler($config, $handlers, $this);

                });
                $this->container->bind(CustomTagRegistry::class, function ($c) {
                    $namespace = 'App\\Helpers\\CustomTags\\';
                    $shorts = (array)$c['config']->get('tags', []);
                    $instances = [];
                    foreach ($shorts as $short) {
                        $class = $namespace . $short;
                        if (class_exists($class)) {
                            $obj = new $class();
                            if ($obj instanceof CustomTagInterface) $instances[] = $obj;
                        }
                    }
                    return TagRegistry::register($instances);
                });
                $this->container->bind(FrontMatterParser::class, Parser::class);
            } catch (ReflectionException $e) {

            }
        }
    }
