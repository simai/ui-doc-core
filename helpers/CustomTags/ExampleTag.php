<?php

namespace App\Helpers\CustomTags;

use App\Helpers\CommonMark\BaseTag;

final class ExampleTag extends BaseTag
{
    public function type(): string { return 'example'; }

    public function baseAttrs(): array
    {
        return ['class' => 'example overflow-hidden radius-1/2 overflow-x-auto'];
    }

}
