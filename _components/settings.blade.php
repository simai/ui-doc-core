<div class="sf-settings-wrap sf-float-wrap">
<button onclick="toggleFloat(this)" title="{{$page->translate('settings')}}" class="sf-button sf-button-settings  sf-button-size-1 sf-icon-button-size-1 sf-button-type-link sf-button--on-surface">
    <i class="sf-icon">settings</i>
</button>
    <div class="sf-settings-menu">
        [!Switch](size=1 title='{{$page->translate('dark')}}' on='{{$page->translate('on')}}' off='{{$page->translate('off')}}')#theme_switch
        [!Switch](size=1 title='{{$page->translate('wide')}}' on='{{$page->translate('on')}}' off='{{$page->translate('off')}}')#widescreen_switch
        [!Radio className=lang_size name=radio_switch](size=1 count=3 text=A title="{{$page->translate('text size')}}" checked=1 description=[{{$page->translate('reduced')}},{{$page->translate('default')}},{{$page->translate('increased')}}])#size_switch
    </div>
</div>
