<?php

    namespace App\Helpers;

    use App\Helpers\CommonMark\CustomTagRegistry;
    use App\Helpers\CommonMark\CustomTagsExtension;
    use League\CommonMark\Environment\Environment;
    use League\CommonMark\Event\DocumentParsedEvent;
    use League\CommonMark\Exception\CommonMarkException;
    use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
    use League\CommonMark\Extension\FrontMatter\FrontMatterExtension;
    use League\CommonMark\MarkdownConverter;
    use Mni\FrontYAML\Parser as FrontYamlParser;
    use TightenCo\Jigsaw\Parsers\FrontMatterParser as BaseFrontMatterParser;

    class Parser extends BaseFrontMatterParser
    {
        private MarkdownConverter $md;

        /**
         * @param FrontYamlParser $frontYaml
         * @param CustomTagRegistry $registry
         */
        public function __construct(FrontYamlParser $frontYaml, CustomTagRegistry $registry)
        {
            parent::__construct($frontYaml);

            $env = new Environment();
            $env->addExtension(new CustomTagsExtension($registry));
            $env->addExtension(new CommonMarkCoreExtension());
            $env->addExtension(new FrontMatterExtension());
            $this->md = new MarkdownConverter($env);
        }

        /**
         * @param $content
         * @return string
         * @throws CommonMarkException
         */
        public function parseMarkdownWithoutFrontMatter($content): string
        {
            return (string)$this->md->convert($content);
        }
    }


