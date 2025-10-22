<?php

    namespace App\Helpers\Handlers;

    use App\Helpers\Configurator;
    use Illuminate\Support\Arr;
    use Illuminate\Support\Str;
    use TightenCo\Jigsaw\Handlers\CollectionItemHandler as Base;
    use TightenCo\Jigsaw\File\OutputFile;
    use Illuminate\Support\Collection as BaseCollection;
    class CustomCollectionItemHandler extends Base
    {
        /** @var \Illuminate\Support\Collection */
        protected BaseCollection $myHandlers;
        protected BaseCollection $customConfig;

        protected Configurator $configurator;

        protected string $docDir = '';
        protected array $docDirArray = [];

        public function __construct(BaseCollection $config, $handlers, Configurator $configurator)
        {
            parent::__construct($config, $handlers);
            $this->customConfig = $config;
            $this->configurator = $configurator;
            $this->myHandlers = collect($handlers);
            $this->docDir = trim($_ENV['DOCS_DIR']);
            $this->docDirArray = explode('/', $this->docDir);
        }

        public function handle($file, $pageData)
        {
            $handler = $this->myHandlers->first(function ($handler) use ($file) {
                return $handler->shouldHandle($file);
            });
            $name = collect(explode('/', trim(str_replace('\\', '/', $file->getRelativePath()), '/')))
                ->skip(count($this->docDirArray) + 1)
                ->implode('/');
            $name = $name . '/' . str_replace('.md','',$file->getFilename());

            $pageData->setPageVariableToCollectionItem($this->getCollectionName($file), $name);

            if ($pageData->page === null) {
                return null;
            }


            return $handler->handleCollectionItem($file, $pageData)
                ->map(function ($outputFile, $templateToExtend) use ($file) {
                    if ($templateToExtend) {
                        $outputFile->data()->setExtending($templateToExtend);
                    }

                    $path = $outputFile->data()->page->getPath();
                    return $path ? new OutputFile(
                        $file,
                        dirname($path),
                        basename($path, '.' . $outputFile->extension()),
                        $outputFile->extension(),
                        $outputFile->contents(),
                        $outputFile->data(),
                    ) : null;
                })->filter()->values();
        }


        public function shouldHandle($file): bool
        {
            return $this->isInCollectionDirectory($file)
                && !Str::startsWith($file->getFilename(), ['.', '_']);
        }

        private function isInCollectionDirectory($file): bool
        {
            $base = $file->topLevelDirectory();
            return $base === $this->docDirArray[0] && $this->hasCollectionNamed($this->getCollectionName($file));
        }

        private function hasCollectionNamed($candidate): bool
        {
            return Arr::get($this->customConfig, 'collections.' . $candidate) !== null;
        }

        private function getCollectionName($file): string
        {

            return $this->getName($file);
        }

        protected function getName($file): string {
            if(!count($this->docDirArray)) {
                return '';
            }
            return collect(explode('/', trim(str_replace('\\', '/', $file->getRelativePath()), '/')))
                ->take(count($this->docDirArray) + 1)
                ->implode('-');
        }
    }
