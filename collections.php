<?php

    use Illuminate\Support\Str;

    $collections = [];
    foreach (glob('./source/_docs-*', GLOB_ONLYDIR) as $dir) {
        $lang = str_replace('_docs-', '', basename($dir));

        $collections["docs-$lang"] = [
            'directory' => basename($dir),
            'language' => $lang,
            'extends' => '_core._layouts.documentation',
            'filter' => fn($page) => $page->_meta->extension === 'md',
            'path' => function ($page) use ($lang) {
                $relative = str_replace('\\', '/', $page->_meta->relativePath);
                $rest = trim(Str::after($relative, "$lang/"), '/');
                return "$lang/" . trim(
                        rtrim($rest, '/') . (
                        basename($rest) !== $page->_meta->filename
                            ? '/' . $page->_meta->filename
                            : ''
                        ),
                        '/'
                    );
            },
        ];
    }

    return $collections;
