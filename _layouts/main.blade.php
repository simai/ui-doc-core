<!DOCTYPE html>

<html lang="{{$page->defaultLocale}}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="canonical" href="{{ $page->getUrl() }}">
        <meta name="description" content="{{ $page->description }}">
        <title>{{ $page->title }}</title>
        <link rel="stylesheet" href="{{ mix('css/main.css', '/_core/assets/build') }}">
        <script defer src="{{ mix('js/main.js', '/_core/assets/build') }}"></script>
    </head>
    <body class="text-gray-900 antialiased">
        @yield('body')
    </body>
</html>
