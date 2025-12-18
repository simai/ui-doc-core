<?php

    use Illuminate\Support\Str;

    $collections = [];

    /**
     * Collect directories (relative to language root) whose .settings.php declares menu['index'].
     */
    function collectIndexMenuDirs(string $langDir): array
    {
        $dirs = [];

        if (! is_dir($langDir)) {
            return $dirs;
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($langDir, FilesystemIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if (! $file->isFile() || $file->getFilename() !== '.settings.php') {
                continue;
            }

            $config = @include $file->getPathname();
            if (! is_array($config) || empty($config['menu']) || ! is_array($config['menu'])) {
                continue;
            }

            if (! array_key_exists('index', $config['menu'])) {
                continue;
            }

            $relative = trim(str_replace('\\', '/', str_replace($langDir, '', $file->getPath())), '/');
            $dirs[$relative] = true;
        }

        return $dirs;
    }

    $collectionName = collect(explode('/', trim(str_replace('\\', '/', $_ENV['DOCS_DIR']), '/')))
        ->implode('-');


    foreach (glob('./source/' . $_ENV['DOCS_DIR'] . '/*', GLOB_ONLYDIR) as $dir) {
        $lang = basename($dir);
        $indexMenuDirs = collectIndexMenuDirs($dir);

        $collections["{$collectionName}-{$lang}"] = [
            'directory' => basename('/source/' . $_ENV['DOCS_DIR']),
            'language'  => $lang,
            'extends'   => '_core._layouts.documentation',
            'filter' => fn ($page) => $page->_meta->extension === 'md',
            'path' => function ($page) use ($lang, $indexMenuDirs) {
                $relative = str_replace('\\', '/', $page->_meta->relativePath);
                $filename = $page->_meta->filename;


                if ($filename === 'index' && $relative !== '' && isset($indexMenuDirs[$relative])) {
                    $page->_meta->indexAsPage = true;
                    return $lang . '/' . $relative . '/index';
                }

                if ($filename === 'index') {
                    return $lang . ($relative ? '/' . $relative : '');
                }

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
