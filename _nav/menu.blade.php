@php
$path = str_replace('\\', '/', $page->getPath());
$locale = explode('/', $path);
$current = 'ru';
$locales =  array_keys($page->locales->toArray());
foreach ($locale as $segment) {
    if (in_array($segment, $locales)) {
        $current = $segment;
        break;
    }
}
$page->configurator->setLocale($current);
@endphp

@include('_core._nav.menu-item')
