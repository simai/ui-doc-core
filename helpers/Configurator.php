<?php

    namespace App\Helpers;

    use RecursiveDirectoryIterator;
    use RecursiveIteratorIterator;

    class Configurator
    {

        public array $locales;
        public array $settings;
        public string $locale = 'ru';

        public function __construct( $locales)
        {
            $this->locales = array_keys($locales->toArray());
            $this->makeSettings();
        }

        private function array_set_deep(&$array, $path, $value): void
        {
            $segments = explode('/', $path);
            $current = &$array;

            foreach ($segments as $segment) {
                if (!isset($current['pages'][$segment])) {
                    $current['pages'][$segment] = [];
                }
                $current = &$current['pages'][$segment];
            }

            // Вставляем настройки в 'current'
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
                        $this->array_set_deep($settings, $relativePath, include $file->getPathname());
                    }
                }
                $this->settings[$locale] = $settings['pages'] ?? [];
            }
        }

        public function setLocale(string $locale): void
        {
            $this->locale = $locale;
        }

        public function getItems(): array {
            return  $this->settings[$this->locale] ?? [];
        }


    }
