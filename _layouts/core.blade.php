@php
$hasSha = $page->sha ?? 'latest';
@endphp
<link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
<script>
    window.sfPath = `https://cdn.jsdelivr.net/gh/simai/ui@${@json($hasSha)}/distr/`;
</script>
<script src="{{'https://cdn.jsdelivr.net/gh/simai/ui@'. $hasSha . '/distr/core/js/core.js'}}"></script>
<link rel="stylesheet" href="{{'https://cdn.jsdelivr.net/gh/simai/ui@'. $hasSha . '/distr/core/css/core.css'}}"/>
