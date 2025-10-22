<div class="sf-more-wrap sf-float-wrap">
    <button onclick="toggleFloat(this)" title="{{$page->translate('actions')}}" class="sf-button sf-button-settings  sf-button-size-1 sf-icon-button-size-1 sf-button-type-link sf-button--on-surface">
        <i class="sf-icon">more_vert</i>
    </button>
    <div class="sf-more-menu">
        <div class="flex sf-more-menu_item">
            <button onclick="window.open(`{{$page->github}}blob/main/source/{{$page->gitHubUrl()}}`, '_blank')" title="Изменить статью" class="sf-button text-left sf-button-settings sf-button-size-1 sf-button-type-link sf-button--on-surface">
                <i class="sf-icon">edit</i>
                <span class="sf-button--text-container">{{$page->translate('edit article')}}</span>
            </button>
        </div>
        <div class="flex sf-more-menu_item">
            <button onclick="setIssue(`{{$page->github}}`)" title="Сообщить об ошибке" class="sf-button text-left sf-button-settings  sf-button-size-1 sf-button-type-link sf-button--on-surface">
                <i class="sf-icon">bug_report</i>
                <span class="sf-button--text-container">{{$page->translate('report a bug')}}</span>
            </button>
        </div>
    </div>
</div>
