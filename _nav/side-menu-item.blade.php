@php
    $items = $sub ?? $page->configurator->getItems($page->locale());
    $level = $level ?? 0;
    $isSub = $isSub ?? false;
    $prefix = $prefix ?? '';
@endphp

<div class="sf-side-menu-button-pannel" style = "display: inline-flex; color: var(--sf-on-surface);">
    <button class="sf-button sf-button--1/2 sf-button--on-surface-transparent sf-button--borderless side-menu-instrument">
        <i class="sf-icon">Fullscreen</i>
    </button>
    <button class="sf-button sf-button--1/2 sf-button--on-surface-transparent sf-button--borderless side-menu-instrument">
        <i class="sf-icon">Arrow_Range</i>
    </button>
    <button class="sf-button sf-button--1/2 sf-button--on-surface-transparent sf-button--borderless side-menu-instrument">
        <i class="sf-icon">Bug_Report</i>
    </button>
</div>
<h5 class = "sf-side-menu-header">Название</h5>
<ul class = "sf-side-menu-list">
    <li class = "sf-side-menu-list-item sf-side-menu-list-item--active">
        <a href="#">Классы</a>
    </li>
    <li class = "sf-side-menu-list-item">
        <a href="#">Описание</a>
    </li>
    <li class = "sf-side-menu-list-item">
        <a href="#"> shrink</a>
    </li>
    <li class = "sf-side-menu-list-item">
        <a href="#">shrink-none</a>
    </li>
</ul>

