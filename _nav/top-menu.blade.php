<div class="sf-menu-container items-cross-center flex relative overflow-hidden">
    <button onclick="menuScroll(this, false)" class="sf-menu-scroll left absolute hidden" type="button">
        <i class="sf-icon">chevron_left</i>
    </button>
    <div id="top_menu" class="sf-menu truncate">
        <div class="sf-menu-item">
            <a href="#"><?= $page->translate('concept'); ?></a>
        </div>
        <div class="sf-menu-item">
            <a href="#"><?= $page->translate('core'); ?></a>
        </div>
        <div class="sf-menu-item">
            <a href="#"><?= $page->translate('utilities'); ?></a>
        </div>
        <div class="sf-menu-item">
            <a href="#"><?= $page->translate('components'); ?></a>
        </div>
        <div class="sf-menu-item">
            <a href="#"><?= $page->translate('smart components'); ?></a>
        </div>
    </div>
    <button onclick="menuScroll(this, true)" class="sf-menu-scroll right absolute" type="button">
        <i class="sf-icon">chevron_right</i>
    </button>
</div>
