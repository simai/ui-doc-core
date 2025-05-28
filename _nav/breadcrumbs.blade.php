@php
    $breadcrumbs_array = $page->generateBreadcrumbs();
    $home =  $page->isHome();
@endphp
@if(!$home)
    <section>

        <ul class="sf-breadcrumb">


            @foreach($breadcrumbs_array as $key => $item)
                @if($key === 0)
                    <li class="sf-breadcrumb-item">
                        <a href="{{$item['path']}}">
                            <i class="color-on-surface sf-icon sf-icon-medium">home</i>
                        </a>
                        <i class="sf-icon sf-icon-light">chevron_right</i>
                    </li>
                @else
                    <li class="sf-breadcrumb-item text-1/2 ">
                        @if(isset($item['path']))
                            <a href="{{$item['path']}}">{{$item['label']}}</a>
                        @else
                            <span>{{$item['label']}}</span>
                        @endif

                        @if(!$loop->last)
                            <i class="sf-icon sf-icon-light">chevron_right</i>
                        @endif
                    </li>
                @endif

            @endforeach
        </ul>
    </section>
@endif
