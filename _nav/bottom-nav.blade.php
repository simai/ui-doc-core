@php
    $navigation = $page->getNavItems();
@endphp

<section>
    <div class="bottom--navigation">
        <div class="bottom--navigation-items flex justify-between">
            @foreach($navigation as $key => $item)
                <div class="bottom--navigation-item_{{$key}}">
                    <a class="flex" href="{{$item['path']}}">{{$item['label']}}</a>
                </div>
            @endforeach
        </div>
    </div>
</section>
