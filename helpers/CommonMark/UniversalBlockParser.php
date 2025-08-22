<?php
    namespace App\Helpers\CommonMark;

    use League\CommonMark\Node\Block\AbstractBlock;
    use League\CommonMark\Parser\Block\AbstractBlockContinueParser;
    use League\CommonMark\Parser\Block\BlockContinue;
    use League\CommonMark\Parser\Block\BlockContinueParserInterface;
    use League\CommonMark\Parser\Block\BlockStart;
    use League\CommonMark\Parser\Block\BlockStartParserInterface;
    use League\CommonMark\Parser\Cursor;
    use League\CommonMark\Parser\MarkdownParserStateInterface;

    final class UniversalBlockParser implements BlockStartParserInterface
    {
        /**
         * @param CustomTagRegistry $registry
         */
        public function __construct(private CustomTagRegistry $registry) {}

        /**
         * @param Cursor $cursor
         * @param MarkdownParserStateInterface $state
         * @return BlockStart|null
         */
        public function tryStart(Cursor $cursor, MarkdownParserStateInterface $state): ?BlockStart
        {
            $line = $cursor->getLine();

            foreach ($this->registry->getSpecs() as $spec) {

                if (!preg_match($spec->openRegex, $line, $m)) {
                    continue;
                }


                $active = $state->getActiveBlockParser();
                if ($active && method_exists($active, 'getBlock')) {
                    $blk = $active->getBlock();
                    if ($blk instanceof CustomTagNode
                        && $blk->getType() === $spec->type
                        && !$spec->allowNestingSame
                    ) {
                        return BlockStart::none();
                    }
                }


                $cursor->advanceToEnd();

                $attrStr   = $m['attrs'] ?? '';
                $userAttrs = Attrs::parseOpenLine($attrStr);
                $attrs     = Attrs::merge($spec->baseAttrs, $userAttrs);


                $node = new CustomTagNode(
                    $spec->type,
                    $attrs,
                    ['openMatch' => $m, 'attrStr' => $attrStr]
                );


                if ($spec->attrsFilter instanceof \Closure) {
                    $node->setAttrs(($spec->attrsFilter)($node->getAttrs(), $node->getMeta()));
                }


                return BlockStart::of(new class($spec, $node) extends AbstractBlockContinueParser {
                    /**
                     * @param CustomTagSpec $spec
                     * @param CustomTagNode $node
                     */
                    public function __construct(
                        private CustomTagSpec $spec,
                        private CustomTagNode $node
                    ) {}

                    /**
                     * @return AbstractBlock
                     */
                    public function getBlock(): AbstractBlock { return $this->node; }

                    /**
                     * @return bool
                     */
                    public function isContainer(): bool { return true; }

                    /**
                     * @return bool
                     */
                    public function canHaveLazyContinuationLines(): bool { return false; }


                    /**
                     * @param AbstractBlock $childBLock
                     * @return bool
                     */
                    public function canContain(AbstractBlock $childBLock): bool { return true; }

                    /**
                     * @param Cursor $cursor
                     * @param BlockContinueParserInterface $active
                     * @return BlockContinue|null
                     */
                    public function tryContinue(Cursor $cursor, BlockContinueParserInterface $active): ?BlockContinue
                    {
                        $line = $cursor->getLine();

                        if ($this->spec->closeRegex === null) {
                            return BlockContinue::finished();
                        }

                        if (preg_match($this->spec->closeRegex, $line)) {
                            $cursor->advanceToEnd();
                            return BlockContinue::finished();
                        }

                        return BlockContinue::at($cursor);
                    }

                    /**
                     * @param string $line
                     * @return void
                     */
                    public function addLine(string $line): void {}

                    /**
                     * @return void
                     */
                    public function closeBlock(): void {}
                })->at($cursor);
            }

            return BlockStart::none();
        }
    }
