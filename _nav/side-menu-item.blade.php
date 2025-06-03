<div class="sf-side-menu-button-pannel" style = "display: inline-flex; color: var(--sf-on-surface);">
    <button class="sf-button sf-button--1 sf-button--on-surface-transparent sf-button--borderless side-menu-instrument">
        <i class="sf-icon">Fullscreen</i>
    </button>
    <button class="sf-button sf-button--1 sf-button--on-surface-transparent sf-button--borderless side-menu-instrument sf-size-switcher">
        <i class="sf-icon sf-size-switcher--default">Arrow_Range</i>
        <i class="sf-icon sf-size-switcher--expanded">Chevron_Right</i>
        <i class="sf-icon sf-size-switcher--expanded">Chevron_Left</i>
    </button>
    <button class="sf-button sf-button--1 sf-button--on-surface-transparent sf-button--borderless side-menu-instrument">
        <i class="sf-icon">Bug_Report</i>
    </button>
</div>
<h5 class = "sf-side-menu-header">Навигация</h5>
@if($page->headings && count($page->headings))
    <ul class="sf-side-menu-list flex flex-col">
        @foreach($page->headings as $heading)
            <li class="sf-side-menu-list-item sf-side-menu-list-item--{{ $heading['level'] }}">
                <a href="#{{ $heading['anchor'] }}">{{ $heading['text'] }}</a>
            </li>
        @endforeach
    </ul>
@endif

<div class="table-of-contents">

</div>


