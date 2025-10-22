<?php

    namespace App\Helpers\Handlers;

    use Illuminate\Filesystem\Filesystem;
    use Illuminate\Support\Arr;
    use Illuminate\Support\Str;
    use TightenCo\Jigsaw\Collection\Collection;
    use TightenCo\Jigsaw\Collection\CollectionItem;
    use TightenCo\Jigsaw\Console\ConsoleOutput;
    use TightenCo\Jigsaw\File\InputFile;
    use TightenCo\Jigsaw\IterableObject;
    use TightenCo\Jigsaw\IterableObjectWithDefault;
    use TightenCo\Jigsaw\Loaders\CollectionDataLoader as Base;
    use TightenCo\Jigsaw\PageVariable;


    class CollectionDataLoader extends Base
    {
        private $fs;

        private $cO;

        private $pR;

        private $h;

        private $s;

        private $pS;

        private $cS;

        public function __construct(
           Filesystem $filesystem,
            ConsoleOutput                     $consoleOutput,
                                              $pathResolver,
                                              $handlers = []
        ) {
            parent::__construct($filesystem, $consoleOutput, $pathResolver, $handlers);
            $this->fs = $filesystem;
            $this->pR = $pathResolver;
            $this->h = collect($handlers);
            $this->cO = $consoleOutput;
        }

        public function load($siteData, $source): array
        {
            $this->s = $source;
            $this->pS = $siteData->page;
            $this->cS = collect($siteData->collections);
            $this->cO->startProgressBar('collections');


            $collections = $this->cS->map(function ($collectionSettings, $collectionName) {
                $collection = CollectionHandler::withSettings($collectionSettings, $collectionName);
                $collection->loadItems($this->buildCollection($collection));

                return $collection->updateItems($collection->map(function ($item) {
                    return $this->addCollectionItemContent($item);
                }));
            });

            return $collections->all();
        }

        private function buildCollection($collection)
        {
            $collectionPath = explode('-', $collection->name);
            $collectionPath = implode('/', $collectionPath);
            $path = "{$this->s}/{$collectionPath}";
            if (! $this->fs->exists($path)) {
                return collect();
            }

            return collect($this->fs->files($path))
                ->reject(function ($file) {
                    return Str::startsWith($file->getFilename(), '_');
                })->filter(function ($file) {
                    return $this->hasHandler($file);
                })->tap(function ($files) {
                    $this->cO->progressBar('collections')->addSteps($files->count());
                })->map(function ($file) {
                    return new InputFile($file);
                })->map(function ($inputFile) use ($collection) {
                    $this->cO->progressBar('collections')->advance();

                    return $this->buildCollectionItem($inputFile, $collection);
                });
        }

        private function buildCollectionItem($file, $collection): CollectionItem
        {
            $data = $this->pS
                ->merge(['section' => 'content'])
                ->merge($collection->settings)
                ->merge($this->getHandler($file)->getItemVariables($file));
            $data->put('_meta', new IterableObject($this->getMetaData($file, $collection, $data)));
            $path = $this->getPath($data, $collection);
            $data->_meta->put('path', $path)->put('url', $this->buildUrls($path));

            return CollectionItem::build($collection, $data);
        }

        private function addCollectionItemContent($item)
        {
            $file = $this->fs->getFile($item->getSource(), $item->getFilename() . '.' . $item->getExtension());

            if ($file) {
                $item->setContent($this->getHandler($file)->getItemContent($file));
            }

            return $item;
        }

        private function hasHandler($file): bool
        {
            return $this->h->contains(function ($handler) use ($file) {
                return $handler->shouldHandle($file);
            });
        }

        private function getHandler($file)
        {
            return $this->h->first(function ($handler) use ($file) {
                return $handler->shouldHandle($file);
            });
        }

        private function getMetaData($file, $collection, $data): array
        {
            $filename = $file->getFilenameWithoutExtension();
            $baseUrl = $data->baseUrl;
            $relativePath = $file->getRelativePath();
            $extension = $file->getFullExtension();
            $collectionName = $collection->name;
            $collection = $collectionName;
            $source = $file->getPath();
            $modifiedTime = $file->getLastModifiedTime();

            return compact('filename', 'baseUrl', 'relativePath', 'extension', 'collection', 'collectionName', 'source', 'modifiedTime');
        }

        private function buildUrls($paths): ?IterableObjectWithDefault
        {
            $urls = collect($paths)->map(function ($path) {
                $pattern = '#' . preg_quote($_ENV['DOCS_DIR'], '#') . '#';
                $path = preg_replace($pattern, '', $path);
                return rightTrimPath($this->pS->get('baseUrl')) . '/' . trimPath($path);
            });

            return $urls->count() ? new IterableObjectWithDefault($urls) : null;
        }

        private function getPath($data, $collection): ?IterableObjectWithDefault
        {
            $links = $this->pR->link(
                $data->path,
                new PageVariable($data),
                Arr::get($collection->settings, 'transliterate', true),
            );

            return $links->count() ? new IterableObjectWithDefault($links) : null;
        }
    }
