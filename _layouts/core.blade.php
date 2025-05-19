<script>
    window.sfPath = `https://cdn.jsdelivr.net/gh/simai/ui@${@json($page->sha ?? 'latest')}/distr/`;
</script>
<script src="{{'https://cdn.jsdelivr.net/gh/simai/ui@' . $page->sha . '/distr/core/js/core.js'}}"></script>
<link rel="stylesheet" href={{'https://cdn.jsdelivr.net/gh/simai/ui@' .  $page->sha . '/distr/core/css/core.css'}}/>
