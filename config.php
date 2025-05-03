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
        ],
        'defaultLocale' => 'ru',
        'lang_path' => 'source/lang',
        'collections' => require_once('source/_core/collections.php'),
        'isActive' => function ($page, $path) {
            return Str::endsWith(trimPath($page->getPath()), trimPath($path));
        },
        'isActiveParent' => function ($page, $slug) {
            $path = trim(trimPath($page->getPath()), '/');

            $segments = explode('/', $path);
            array_shift($segments);


            return in_array($slug, $segments);
        },
        'url' => function ($page, $path) {
            return Str::startsWith($path, 'http') ? $path : '/' . trimPath($path);
        },
    ];
