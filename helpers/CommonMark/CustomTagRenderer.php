<?php

    namespace App\Helpers\CommonMark;

    use App\Helpers\Interface\CustomTagInterface;
    use League\CommonMark\Node\Node;
    use League\CommonMark\Renderer\ChildNodeRendererInterface;
    use League\CommonMark\Renderer\NodeRendererInterface;
    use League\CommonMark\Util\HtmlElement;

    final readonly class CustomTagRenderer implements NodeRendererInterface
    {
        /**
         * @param CustomTagRegistry $registry
         */
        public function __construct(private CustomTagRegistry $registry)
        {
        }


        /**
         * @param Node $node
         * @param ChildNodeRendererInterface $childRenderer
         * @return mixed
         */
        public function render(Node $node, ChildNodeRendererInterface $childRenderer): mixed
        {
            if (!$node instanceof CustomTagNode) return '';
            $spec = $this->registry->get($node->getType());

            if ($spec?->renderer instanceof \Closure) {
                return ($spec->renderer)($node, $childRenderer);
            }
            return new \League\CommonMark\Util\HtmlElement(
                $spec?->htmlTag ?? 'div',
                $node->getAttrs(),
                $childRenderer->renderNodes($node->children())
            );
        }
    }
