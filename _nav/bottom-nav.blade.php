@php
    $navigation = $page->getNavItems();
@endphp

<section>
    <div class="bottom--navigation flex flex-col">
        <a class="flex" href="#">
            <button class = "sf-button sf-button--on-surface-transparent sf-button--borderless">
                <i class = "sf-icon">edit</i>
                 Изменить страницу
            </button>
        </a>
        <div class="bottom--navigation-items flex content-main-between">
            @foreach($navigation as $key => $item)

                <a class="flex" href="{{$item['path']}}">
                    <button class = "sf-button sf-button--on-surface-transparent sf-button--borderless bottom--navigation-item_{{$key}}">
                        @if($key == "prev")
                        <i class = "sf-icon">chevron_left</i>
                        @endif

                            {{$item['label']}}

                        @if($key == "next")
                        <i class = "sf-icon">chevron_right</i>
                        @endif
                    </button>
                </a>
            @endforeach
        </div>
    </div>
</section>
