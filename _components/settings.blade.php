<div class="sf-settings-wrap">
<button onclick="toggleSettings(this)" title="{{$page->translate('settings')}}" class="sf-button sf-button-settings  sf-button--on-surface-transparent sf-button--borderless">
    <i class="sf-icon">settings</i>
</button>
    <div class="sf-settings-menu">
        [!Switch](size=1 title=Темная on=Включена off=Выключена)#theme_switch
        [!Switch](size=1 title=Широкий формат on=Включен off=Выключен)#widescreen_switch
        [!Radio className=lang_size name=radio_switch](size=1 count=3 text=A title="Размер текста" checked=1 description=[Уменьшенный,По умолчанию,Увеличенный])#size_switch
    </div>
</div>
