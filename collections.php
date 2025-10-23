<?php


    use Illuminate\Support\Str;
    $collections = [];
    $collectionName = collect(explode('/', trim(str_replace('\\', '/', $_ENV["DOCS_DIR"]), '/')))->implode('-');
    foreach (glob( './source/'. $_ENV['DOCS_DIR'] . '/*', GLOB_ONLYDIR) as $dir) {
        $lang = basename($dir);
        $collections["{$collectionName}-{$lang}"] = [
            'directory' => basename( '/source/'. $_ENV['DOCS_DIR']),

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
