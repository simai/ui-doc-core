<?php

    namespace App\Helpers\CommonMark;

    use League\CommonMark\Environment\EnvironmentBuilderInterface;
    use League\CommonMark\Extension\ExtensionInterface;


    final class CustomTagsExtension implements ExtensionInterface
    {
        /**
         * @param CustomTagRegistry $registry
         */
        public function __construct(private CustomTagRegistry $registry) {}

        /**
         * @param EnvironmentBuilderInterface $env
         * @return void
         */
        public function register(EnvironmentBuilderInterface $environment): void
        {

//             $env->addInlineParser(new UniversalInlineParser($this->registry), 150);


            $environment->addBlockStartParser(new UniversalBlockParser($this->registry), 0);


            $environment->addRenderer(CustomTagNode::class, new CustomTagRenderer($this->registry));
        }
    }
