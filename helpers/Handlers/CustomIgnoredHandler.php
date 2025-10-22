<?php

    namespace App\Helpers\Handlers;

    use Illuminate\Support\Collection;
    use TightenCo\Jigsaw\Handlers\IgnoredHandler;

    class CustomIgnoredHandler extends IgnoredHandler
    {
        public function shouldHandle($file): bool
        {
            $pattern = '#' . preg_quote($_ENV['DOCS_DIR'], '#') . '#';
            return preg_match('/(^\/*_)/', $file->getRelativePathname()) === 1 || preg_match($pattern, str_replace('\\', '/', $file->getRelativePathname())) === 1;
        }

        public function handle($file, $data): Collection
        {
            return collect([]);
        }
    }
