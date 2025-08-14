<?php

    namespace App\Helpers\CommonMark;

    use League\CommonMark\Environment\EnvironmentBuilderInterface;
    use League\CommonMark\Extension\ExtensionInterface;

    class CustomTagsExtension implements ExtensionInterface
    {
        private CustomTagRegistry $registry;

        public function __construct(CustomTagRegistry $registry)
        {
            $this->registry = $registry;
        }

        public function register(EnvironmentBuilderInterface $environment): void
        {
            $environment->addInlineParser(new UniversalInlineParser($this->registry), 100);
            $environment->addBlockStartParser(new UniversalBlockParser($this->registry), 100);

            $environment->addRenderer(CustomTagNode::class, new CustomTagRenderer((array)$this->registry),100);
        }
    }
