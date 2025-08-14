<?php
    namespace App\Helpers\CommonMark;

    use App\Helpers\Interface\CustomTagInterface;
    use League\CommonMark\Node\Node;
    use League\CommonMark\Renderer\ChildNodeRendererInterface;
    use League\CommonMark\Renderer\NodeRendererInterface;

    readonly class CustomTagRenderer implements NodeRendererInterface
    {
        public function __construct(private array $tagMap) {}

        public function render(Node $node, ChildNodeRendererInterface $childRenderer): string
        {
            if (!$node instanceof CustomTagNode) return '';

            $type = $node->getTagType();

            if (!isset($this->tagMap[$type])) return $node->getContent();

            /** @var CustomTagInterface $tag */
            $tag = $this->tagMap[$type];
            return $tag->getTemplate($node->getContent());
        }
    }
