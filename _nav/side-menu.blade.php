@if($page->headings && count($page->headings))
    <div class="side-menu-navigation">
        <h5 class="sf-side-menu-header">{{$page->translate('navigation')}}</h5>
        <div class="sf-side-menu-list_wrap">
            <ul id="side_menu_list" class="sf-side-menu-list flex flex-col">
                @foreach($page->headings as $heading)
                    @php
                        $padding = $heading['type'] / 2 - 0.5;
                    @endphp
                    <li {{$heading['type'] > 1 ? "style=padding-left:{$padding}rem;"  : ''}} class="sf-side-menu-list-item sf-side-menu-list-item--{{ $heading['level'] }}">
                        <a class="sf-side_item" href="#{{ $heading['id'] }}"><span>{{ $heading['text'] }}</span></a>
                    </li>
                @endforeach
            </ul>
        </div>
        <button onclick="navOpen()" id="sf_segment_close"
                class="sf-button sf-button-segment_close sf-button-size-1 sf-icon-button-size-1 sf-button--on-surface sf-button-type-link side-menu-instrument">
            <i class="sf-icon">close</i>
        </button>
    </div>
@endif

<div class="table-of-contents">

</div>


