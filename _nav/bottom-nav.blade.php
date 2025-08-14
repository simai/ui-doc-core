@php
    $navigation = $page->getNavItems();
@endphp

<section>
    <div class="bottom--navigation flex">
        <div class="bottom--navigation-items w-full flex">
            @foreach($navigation as $key => $item)
                @php
                    $prev = $key == "prev";
                    $text =  $page->translate($prev ? 'previous' : 'next');
                @endphp
                <a class="flex sf-button sf-button--on-surface-transparent sf-button--borderless bottom--navigation-item_{{$key}}"
                   href="{{$item['path']}}">
                    @if($prev)
                        <i class="sf-icon">chevron_left</i>
                    @endif
                    <div class="flex bottom--navigation_text flex-col">
                        <div>{{$text}}</div>
                        <div>{{$item['label']}}</div>
                    </div>
                    @if($key == "next")
                        <i class="sf-icon">chevron_right</i>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</section>
