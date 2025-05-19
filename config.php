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
        'getNavItems' => function ($page) {
            return $page->configurator->getPrevAndNext($page->getPath(), $page->locale());
        },
        'generateBreadcrumbs' => function($page) {
            $crumbs = [];
            $currentPath = '';

            // Добавляем домашнюю страницу
            $crumbs[] = [
                'title' => 'Главная',
                'url' => '/'
            ];

            // Разбиваем путь на части
            $pathParts = explode('/', trim($page->getPath(), '/'));

            // Строим хлебные крошки
            foreach ($pathParts as $i => $part) {
                if (empty($part)) continue;

                $currentPath .= '/' . $part;
                $isLast = $i === count($pathParts) - 1;

                // Получаем метаданные страницы из front matter или используем часть пути
                $pageTitle = $page->title ?? str_replace('-', ' ', ucfirst($part));

                if (!$isLast) {
                    // Для неактивных элементов используем реальные страницы или генерируем
                    $crumbs[] = [
                        'title' => $pageTitle,
                        'url' => $currentPath
                    ];
                } else {
                    // Активная страница (последний элемент)
                    $crumbs[] = [
                        'title' => $page->title ?? $pageTitle,
                        'url' => ''
                    ];
                }
            }

            return $crumbs;
        },
        'locale' => function ($page) {
            $path = str_replace('\\', '/', $page->getPath());
            $locale = explode('/', $path);
            $current = 'ru';
            $locales =  array_keys($page->locales->toArray());
            foreach ($locale as $segment) {
                if (in_array($segment, $locales)) {
                    $current = $segment;
                    break;
                }
            }
            return $current;
        },
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
