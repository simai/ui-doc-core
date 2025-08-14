<?php

    namespace App\Helpers\CommonMark;

    use App\Helpers\Interface\CustomTagInterface;
    use League\CommonMark\Exception\InvalidArgumentException;

    class CustomTagRegistry
    {
        /** @var array<string, CustomTagInterface> */
        private array $tags = [];

        public function __construct(array $tagClassNames)
        {
            foreach ($tagClassNames as $className) {
                $fqcn = "App\\Helpers\\CustomTags\\$className";

                if (!class_exists($fqcn)) {
                    throw new InvalidArgumentException("Custom tag class not found: $fqcn");
                }

                $instance = new $fqcn();

                if (!($instance instanceof CustomTagInterface)) {
                    throw new InvalidArgumentException("$fqcn must implement CustomTagInterface");
                }

                $this->tags[strtolower($className)] = $instance;
            }
        }

        /**
         * @return array<string, CustomTagInterface>
         */
        public function all(): array
        {
            return $this->tags;
        }

        /**
         * Получить тег по типу (например 'example')
         */
        public function get(string $type): ?CustomTagInterface
        {
            return $this->tags[strtolower($type)] ?? null;
        }

        /**
         * Получить все паттерны с типами
         */
        public function getPatterns(): array
        {
            $patterns = [];
            foreach ($this->tags as $type => $tag) {
                $patterns[] = [
                    'type' => $type,
                    'pattern' => $tag->getPattern(),
                    'open' => $tag->getOpeningPattern(),
                    'close' => $tag->getClosingPattern(),
                ];
            }
            return $patterns;
        }
    }
