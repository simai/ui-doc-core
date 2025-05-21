@php
    $items = $sub ?? $page->configurator->getItems($page->locale());
    $level = $level ?? 0;
    $isSub = $isSub ?? false;
    $prefix = $prefix ?? '';
@endphp

<div class="side-menu-button-pannel" style = "display: inline-flex; color: var(--sf-on-surface);">
    <button class="sf-button sf-button--on-surface-transparent sf-button--borderless side-menu-instrument">
        <i class="sf-icon">Fullscreen</i>
    </button>
    <button class="sf-button sf-button--on-surface-transparent sf-button--borderless side-menu-instrument">
        <i class="sf-icon">Arrow_Range</i>
    </button>
    <button class="sf-button sf-button--on-surface-transparent sf-button--borderless side-menu-instrument">
        <i class="sf-icon">Bug_Report</i>
    </button>
</div>
<h5 class = "side-menu-header">Название</h5>
<ul class = "side-menu-list">
    <li>
        <a href="#">Классы</a>
    </li>
    <li>
        <a href="#">Описание</a>
    </li>
    <li>
        <a href="#"> shrink</a>
    </li>
    <li>
        <a href="#">shrink-none</a>
    </li>
</ul>

