<?php

    namespace App\Helpers;

    use RecursiveDirectoryIterator;
    use RecursiveIteratorIterator;

    class Configurator
    {

        public array $locales;
        public array $paths = [];
        public array $settings;

        public array $translations = [];
        public array $headings;
        public array $menu;
        public array $flattenMenu;
        public array $realFlatten;
        public string $locale = 'ru';

        public function __construct($locales)
        {
            $this->locales = array_keys($locales->toArray());
            $this->makeSettings();
            $this->makeLocales();
        }

        private function array_set_deep(&$array, $path, $value, $locale): void
        {
            $segments = explode('/', $path);
            $current = &$array;

            foreach ($segments as $segment) {
                $normalizeSegment = $segment === '' ? $locale : $segment;
                if (!isset($current['pages'][$normalizeSegment])) {
                    $current['pages'][$normalizeSegment] = [];
                }
                $current = &$current['pages'][$normalizeSegment];
            }
            $dir = 'source/_docs-' . $locale;
            if(is_dir($dir . '/' . $path)) {
                if(is_file($dir . '/' . $path .'/index.md') || is_file($dir . '/' . $path . '/' . $path. '.md')) {
                    $value['has_index'] = true;
                } else {
                    $value['has_index'] = false;
                }
            };
            $current['current'] = $value;
        }

        public function generateBreadCrumbs($locale, $segments)
        {
            $items = [];
            $path = '';
            foreach ($segments as $segment) {
                $path  .= '/' .$segment ;
                foreach ($this->realFlatten[$locale] as $value) {
                    if($value['path']) {
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

        private function eachArray($item, $segment)
        {
            if (is_array($item)) {
                foreach ($item as $key => $value) {
                    if ($key === $segment) {
                        return $value;
                    }
                    $this->eachArray($value, $segment);
                }
            }
        }

        public function makeLocales(): void
        {
            foreach ($this->locales as $locale) {
                $locales = [];
                $file = 'source/_docs-' . $locale . '/.lang.php';
                if (is_file($file)) {
                    $content = include $file;
                    $this->translations[$locale] = $content;
                }
            }
        }

        public function makeSettings(): void
        {
            foreach ($this->locales as $locale) {
                $settings = [];
                $dir = 'source/_docs-' . $locale;
                if (is_dir($dir)) {
                    foreach (new RecursiveIteratorIterator(
                                 new RecursiveDirectoryIterator($dir . '/')
                             ) as $file) {
                        if ($file->isFile() && $file->getFilename() === '.settings.php') {
                            $relativePath = str_replace($dir, '', dirname($file->getPathname()));
                            $relativePath = ltrim(str_replace('\\', '/', $relativePath), '/');
                            $this->array_set_deep($settings, $relativePath, include $file->getPathname(), $locale);
                        }
                    }
                    $this->settings[$locale] = $settings['pages'] ?? [];
                    $pages = $this->makeFlatten($settings['pages'], $locale);
                    $this->flattenMenu[$locale] = array_filter($pages['flat'], function ($item) {
                      return $item['path'] !== null;
                    });
                    $this->flattenMenu[$locale] = array_values($this->flattenMenu[$locale]);
                    $this->realFlatten[$locale] = $pages['flat'];
                    $this->menu[$locale] = $this->buildMenuTree($settings['pages'] ?? [], '' , $locale);
                }
            }
        }

        public function makeFlatten(array $items, string $locale): array
        {
            $pages = [
                'flat' => [],
            ];
            $this->makeMenu($items, $pages,'', $locale);
            return $pages;
        }

        public function getMenu(string $locale): array
        {
            return $this->menu[$locale];
        }

        function buildMenuTree(array $items, string $prefix = '', string $locale = 'ru'): array
        {
            $tree = [];

            foreach ($items as $slug => $item) {
                $title = $item['current']['title'] ?? null;
                $hasSub = !empty($item['pages']);
                $menu = $item['current']['menu'] ?? [];

                $currentPath = $prefix ? $prefix . '/' . $slug : $slug;

                if ($prefix === '' && $slug === $locale) {
                    $fullPath = '/' . $locale;
                } else {
                    $fullPath = '/' . $locale . '/' . trim($currentPath, '/');
                }

                $tree[$fullPath] = [
                    'title' => $title,
                    'path'  => $fullPath,
                    'children' => [],
                ];

                if (is_array($menu)) {
                    foreach ($menu as $menuKey => $menuLabel) {
                        $menuPath = $fullPath . '/' . $menuKey;
                        $tree[$fullPath]['children'][$menuPath] = [
                            'title' => $menuLabel,
                            'path'  => $menuPath,
                            'children' => [],
                        ];
                    }
                }

                if ($hasSub) {
                    $tree[$fullPath]['children'] += $this->buildMenuTree($item['pages'], $currentPath, $locale);
                }
            }

            return $tree;
        }

        public function makeMenu(array $items, array &$pages, string $prefix = '', string $locale = 'ru'): void
        {
            foreach ($items as $key => $value) {
                $hasPages = isset($value['pages']) && is_array($value['pages']);
                $path = trim($prefix . '/' . $key, '/');
                $setItem = false;
                if(isset($value['current']) && $value['current']['has_index']) {
                    $fullPath = trim($path, '/');
                    $finalPath = str_ends_with($fullPath, $path) ? $fullPath : trim($fullPath . '/' . $path, '/');
                    $menuPath = ($path === $locale ? '' : '/' . $locale) . '/' . $finalPath;
                    $pages['flat'][] = [
                        'key' => $path,
                        'path' => $menuPath,
                        'label' => $value['current']['title'],
                    ];
                    $setItem = true;
                }
                if (isset($value['current']['menu']) && is_array($value['current']['menu'])) {
                    if(!$setItem) {
                        $fullPath = trim($path, '/');
                        $finalPath = str_ends_with($fullPath, $path) ? $fullPath : trim($fullPath . '/' . $path, '/');
                        $menuPath = ($path === $locale ? '' : '/' . $locale) . '/' . $finalPath;
                        $pages['flat'][] = [
                            'key' => $path,
                            'path' => null,
                            'navPath' => $menuPath,
                            'label' => $value['current']['title'],
                        ];
                    }
                    foreach ($value['current']['menu'] as $menuKey => $menuLabel) {
                        $fullPath = trim($path, '/');
                        $finalPath = str_ends_with($fullPath, $menuKey) ? $fullPath : trim($fullPath . '/' . $menuKey, '/');
                        $menuPath = ($path === $locale ? '' : '/' . $locale) . '/' . $finalPath;
                        $pages['flat'][] = [
                            'key' => $menuKey,
                            'path' => $menuPath,
                            'label' => $menuLabel,
                        ];
                    }
                }

                if ($hasPages) {
                    $this->makeMenu($value['pages'], $pages, $path, $locale);
                }
            }
        }


        public function makeUniqueHeadingId($relativePath, $level, $index): string
        {
            $base = $relativePath . '-' . $level . '-' . $index;
            return 'h-' . substr(md5($base), 0, 12);
        }

        public function setHeading($path, $headings): void
        {
            $this->headings[$path] = $headings;
        }





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

        public function getPrevAndNext(string $path, string $locale): array
        {
            $flattenNav = $this->flattenMenu[$locale];
            $returnArr = [];
            $needly = 0;
            foreach ($flattenNav as $key => $value) {
                if(!$value['path']) {
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

        public function setLocale(string $locale): void
        {
            $this->locale = $locale;
        }

        public function setPaths(array $paths): void
        {
            $this->paths = array_merge($paths, $this->paths);
        }

        public function getTranslate($text, $locale): string
        {
            $text = $this->translations[$locale][$text];
            return $text ?? '';

        }

        public function getItems($locale): array
        {
            return $this->settings[$locale] ?? [];
        }
    }
