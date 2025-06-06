<?php

    use Illuminate\Support\Str;

    return [
        'baseUrl' => '',
        'production' => false,
        'siteName' => 'Simai Documentation',
        'siteDescription' => 'Simai framework documentation',

        'docsearchApiKey' => env('DOCSEARCH_KEY'),
        'docsearchIndexName' => env('DOCSEARCH_INDEX'),
        'locales' => [
            'en' => 'English',
            'ru' => 'Русский',
            'de' => 'Deutsch',
            'es' => 'Spanish',
        ],
        'defaultLocale' => 'ru',
        'lang_path' => 'source/lang',
        'getNavItems' => function ($page) {
            return $page->configurator->getPrevAndNext($page->getPath(), $page->locale());
        },
        'generateBreadcrumbs' => function ($page) {
            $currentPath = trim($page->getPath(), '/');
            $locale = $page->locale();
            $segments = $currentPath === '' ? [] : explode('/', $currentPath);
            return $page->configurator->generateBreadCrumbs($locale, $segments);
        },
        'locale' => function ($page) {
            $path = str_replace('\\', '/', $page->getPath());
            $locale = explode('/', $path);
            $current = 'ru';
            $locales = array_keys($page->locales->toArray());
            foreach ($locale as $segment) {
                if (in_array($segment, $locales)) {
                    $current = $segment;
                    break;
                }
            }
            return $current;
        },
        'isHome' => function ($page) {
            $current = trim($page->getPath(), '/');
            return $current === $page->locale();
        },
        'collections' => require_once('source/_core/collections.php'),
        'isActive' => function ($page, $path) {

            return Str::endsWith(trimPath($page->getPath()), trimPath($path));
        },
        'translate' => function ($page, $text) {
            return $page->configurator->getTranslate(trim($text), $page->locale());
        },
        'isActiveParent' => function ($page, $node): bool {
            $currentPath = $page->getPath();
            if ($node['path'] === $currentPath) {
                return true;
            }
            foreach ($node['children'] as $child) {
                if ($page->isActiveParent($child, $currentPath)) {
                    return true;
                }
            }
            return false;
        },
    ];
