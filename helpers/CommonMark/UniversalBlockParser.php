<?php

    namespace App\Helpers\CommonMark;

    use League\CommonMark\Node\Block\AbstractBlock;
    use League\CommonMark\Parser\Block\BlockContinue;
    use League\CommonMark\Parser\Block\BlockContinueParserInterface;
    use League\CommonMark\Parser\Block\BlockStart;
    use League\CommonMark\Parser\Block\BlockStartParserInterface;
    use League\CommonMark\Parser\Cursor;
    use League\CommonMark\Parser\MarkdownParserStateInterface;

    class UniversalBlockParser implements BlockStartParserInterface
    {
        public function __construct(private CustomTagRegistry $registry)
        {
        }

        public function tryStart(Cursor $cursor, MarkdownParserStateInterface $parserState): ?BlockStart
        {
            $line = $cursor->getLine();

            // 1) Не разрешаем старт нового кастом-блока, если уже внутри CustomTagNode
            $active = $parserState->getActiveBlockParser();
            if (method_exists($active, 'getBlock') && $active->getBlock() instanceof CustomTagNode) {
                return BlockStart::none();
            }

            foreach ($this->registry->getPatterns() as $entry) {
                $type  = $entry['type'];
                $open  = $entry['open'];
                $close = $entry['close'];


                if (preg_match($open, $line)) {

                    $cursor->advanceToEnd();

                    return BlockStart::of(new class($type, $close) implements BlockContinueParserInterface {
                        private string $type;
                        private string $close;
                        private ?CustomTagNode $node = null;

                        public function __construct(string $type, string $close)
                        {
                            $this->type  = $type;
                            $this->close = $close;
                            // контейнер; внутри пусть парсится обычный markdown
                            $this->node  = new CustomTagNode($type, '');
                        }

                        public function tryContinue(Cursor $cursor, BlockContinueParserInterface $active): ?BlockContinue
                        {
                            $line = $cursor->getLine();
                            // 3) Закрывающий маркер: тоже съедаем строку и завершаем блок
                            if (preg_match($this->close, $line)) {
                                $cursor->advanceToEnd();
                                return BlockContinue::finished();
                            }

                            // продолжаем парсить детей внутри контейнера
                            return BlockContinue::at($cursor);
                        }

                        public function addLine(string $line): void
                        {
                            // Ничего не копим вручную: это контейнер, дети появятся сами
                        }

                        public function closeBlock(): void
                        {
                        }

                        public function getBlock(): AbstractBlock { return $this->node; }
                        public function isContainer(): bool { return true; }
                        public function canHaveLazyContinuationLines(): bool { return true; }
                        public function canContain(AbstractBlock $childBlock): bool
                        {
                            return !($childBlock instanceof CustomTagNode);
                        }
                    })
                        ->at($cursor);
                }
            }

            return BlockStart::none();
        }

    }
