<?php

    namespace App\Helpers;

    use RecursiveDirectoryIterator;
    use RecursiveIteratorIterator;

    class Configurator
    {

        public array $locales;
        public array $settings;
        public array $flattenMenu;
        public string $locale = 'ru';

        public function __construct( $locales)
        {
            $this->locales = array_keys($locales->toArray());
            $this->makeSettings();
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
            $current['current'] = $value;
        }

        public function makeSettings(): void
        {
            foreach ($this->locales as $locale) {
                $settings = [];
                $dir = 'source/_docs-'. $locale;
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
                $this->flattenMenu[$locale] = $this->makeFlatten($settings['pages'], $locale);
            }
        }

        public function makeFlatten(array $items, string $locale): array
        {
            $flat = [
                ['key' => $locale, 'path' => '/' . $locale, 'label' => $items[$locale]['current']['title']],
            ];
             $this->makeMenu($items, $flat, '', $locale);

             return $flat;
        }

        public function makeMenu(array $items, array &$flat, string $prefix = '', string $locale): void
        {
            foreach ($items as $key => $value) {
                $hasPages = isset($value['pages']) && is_array($value['pages']);
                $path = trim($prefix . '/' . $key, '/');

                if (isset($value['current']['menu']) && is_array($value['current']['menu'])) {
                    foreach ($value['current']['menu'] as $menuKey => $menuLabel) {
                        $fullPath = trim($path, '/');
                        $finalPath = str_ends_with($fullPath, $menuKey) ? $fullPath : trim($fullPath . '/' . $menuKey, '/');
                        $menuPath = ($path === $locale ? '' : '/' . $locale) . '/' . $finalPath;
                        $flat[] = [
                            'key' => $menuKey,
                            'path' => $menuPath,
                            'label' => $menuLabel,
                        ];
                    }
                }

                if ($hasPages) {
                    $this->makeMenu($value['pages'], $flat, $path, $locale);
                }
            }
        }


        public function flattenNav(array $items, array &$flat): array {

            foreach ($items as $key => $value) {
                   if(is_array($value) && $key === 'menu') {
                        $flat = array_merge($flat, $value);
                   } else if(is_array($value)) {
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
                if($value['path'] === $path) {
                    $needly = $key;
                    break;
                }

            }

            if($needly === 0) {
                $returnArr['next'] = $flattenNav[1];
            } else {
                $returnArr['prev'] = $flattenNav[$needly - 1];
                if(isset($flattenNav[$needly + 1])) {
                $returnArr['next'] = $flattenNav[$needly + 1];
                }
            }
            return $returnArr;
        }

        public function setLocale(string $locale): void
        {
            $this->locale = $locale;
        }

        public function getItems($locale): array {
            return  $this->settings[$locale] ?? [];
        }
    }
