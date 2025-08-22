<?php

    namespace App\Helpers\CustomTags;

    use App\Helpers\CommonMark\BaseTag;

    final class ListWrap extends BaseTag
    {
        public function type(): string { return 'links'; }

        public function baseAttrs(): array
        {
            return ['class' => 'links'];
        }

    }
