<div class="sf-more-wrap">
    <button onclick="toggleActions(this)" title="{{$page->translate('actions')}}" class="sf-button sf-button-settings  sf-button--on-surface-transparent sf-button--borderless">
        <i class="sf-icon">more_vert</i>
    </button>
    <div class="sf-more-menu">
        <div class="flex sf-more-menu_item">
            <button onclick="window.open(`{{$page->github}}blob/main/source/{{$page->gitHubUrl()}}`, '_blank')" title="Изменить статью" class="sf-button sf-button-settings  sf-button--on-surface-transparent sf-button--borderless">
                <i class="sf-icon">edit</i>
                <span>Изменить статью</span>
            </button>
        </div>
        <div class="flex sf-more-menu_item">
            <button onclick="setIssue(`{{$page->github}}`)" title="Сообщить об ошибке" class="sf-button sf-button-settings  sf-button--on-surface-transparent sf-button--borderless">
                <i class="sf-icon">bug_report</i>
                <span>Сообщить об ошибке</span>
            </button>
        </div>
    </div>
</div>
