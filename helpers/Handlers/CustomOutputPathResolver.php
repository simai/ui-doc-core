<?php

    namespace App\Helpers\Handlers;

    use TightenCo\Jigsaw\PathResolvers\PrettyOutputPathResolver;

    class CustomOutputPathResolver extends PrettyOutputPathResolver
    {
        protected function stripDocs(string $p): string
        {
            $pattern = '#' . preg_quote($_ENV['DOCS_DIR'], '#') . '#';
            return ltrim(preg_replace($pattern, '', $p), '/');
        }

        protected function lastSegment(string $p): string
        {
            $p = str_replace('\\','/',$p);
            $p = trim($p, '/');
            $parts = $p === '' ? [] : explode('/', $p);
            return end($parts) ?: '';
        }



        public function link($path, $name, $type, $page = 1, $prefix = ''): string
        {
            $path = $this->stripDocs($path);
            $path = trimPath($path);

            $appendName = ($type === 'html' && $name !== 'index' && $this->lastSegment($path) !== $name);

            if ($type === 'html' && $name === 'index') {
                if ($page > 1) {
                    return '/' . leftTrimPath($path . '/') . trimPath($prefix . '/' . $page) . '/';
                }
                return '/' . leftTrimPath($path . '/') ;
            }

            if ($type === 'html' && $name !== 'index') {
                if ($page > 1) {
                    return '/' . leftTrimPath($path . '/') . ($appendName ? $name . '/' : '') . trimPath($prefix . '/' . $page) . '/';
                }
                return '/' . leftTrimPath($path . '/') . ($appendName ? $name . '/' : '');
            }

            return sprintf('/%s%s.%s', leftTrimPath($path . '/'), $name, $type);
        }

        public function path($path, $name, $type, $page = 1, $prefix = ''): string
        {
            $path = $this->stripDocs($path);
            $path = trimPath($path);

            $appendName = ($type === 'html' && $name !== 'index' && $this->lastSegment($path) !== $name);

            if ($type === 'html' && $name === 'index' && $page > 1) {
                return leftTrimPath($path . '/' . trimPath($prefix . '/' . $page) . '/index.html');
            }

            if ($type === 'html' && $name !== 'index') {
                if ($page > 1) {
                    return $path . '/' . ($appendName ? $name . '/' : '') . trimPath($prefix . '/' . $page) . '/index.html';
                }
                return $path . '/' . ($appendName ? $name . '/' : '') . 'index.html';
            }

            if (empty($type)) {
                return sprintf('%s/%s', $path, $name);
            }

            return sprintf('%s/%s.%s', $path, $name, $type);
        }

        public function directory($path, $name, $type, $page = 1, $prefix = ''): string
        {
            $path = $this->stripDocs($path);
            $path = trimPath($path);

            $appendName = ($type === 'html' && $name !== 'index' && $this->lastSegment($path) !== $name);

            if ($type === 'html' && $name === 'index' && $page > 1) {
                return leftTrimPath($path . '/' . trimPath($prefix . '/' . $page));
            }

            if ($type === 'html' && $name !== 'index') {
                if ($page > 1) {
                    return $path . '/' . ($appendName ? $name . '/' : '') . trimPath($prefix . '/' . $page);
                }
                return $path . '/' . ($appendName ? $name : '');
            }

            return $path;
        }
    }
