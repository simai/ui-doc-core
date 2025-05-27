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
    <button class="sf-button sf-button--1/2 sf-button--on-surface-transparent sf-button--borderless side-menu-instrument sf-size-switcher">
        <!--<i class="sf-icon">Arrow_Range</i>-->
        <i class="sf-icon">Chevron_Right</i>
        <i class="sf-icon">Chevron_Left</i>
    </button>
    <button class="sf-button sf-button--1/2 sf-button--on-surface-transparent sf-button--borderless side-menu-instrument">
        <i class="sf-icon">Bug_Report</i>
    </button>
</div>
<h5 class = "sf-side-menu-header">Навигация</h5>
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

<script>
    /*document.addEventListener('DOMContentLoaded', function() {
    const resizeButton = document.querySelector('.sf-size-switcher');
    const contentContainer = document.querySelector('body');
    
    // Проверяем сохраненное состояние при загрузке
    const isExpanded = localStorage.getItem('containerExpanded') === 'true';

    var number;
    if (isExpanded) {
        const containerClasses = [...contentContainer.classList].filter(className => 
            className.startsWith('max-container')
        );

        // Получить полное название класса
        if (containerClasses.length > 0) {
            const fullClassName = containerClasses[0];            
            // Можно извлечь число из класса
            const match = fullClassName.match(/max-container-(\d+)/);
            if (match) {
                number = match[1] + 2;
                contentContainer.classList.remove(containerClasses[0]);
                contentContainer.classList.add('max-container-'+number);
                console.log(`Номер контейнера: ${number}`);
                console.log(localStorage.getItem('containerExpanded'));
            }
        }
    }
    
    let changeNumber; 
    // Обработчик клика на кнопку
    resizeButton.addEventListener('click', function() {
        // Получаем текущее состояние из LocalStorage
        const currentState = localStorage.getItem('containerExpanded');
        
        // Устанавливаем противоположное значение
        const newState = !currentState;
        localStorage.setItem('containerExpanded', newState.toString());
        
        // Применяем изменения к DOM
        if (newState) {
            contentContainer.classList.remove('container-default');
            contentContainer.classList.add('container-expanded');
        } else {
            contentContainer.classList.remove('container-expanded');
            contentContainer.classList.add('container-default');
        }
        
        console.log("New state:", newState);
    });
});*/

document.addEventListener('DOMContentLoaded', function() {
    const resizeButton = document.querySelector('.sf-size-switcher');
    const contentContainer = document.querySelector('body');
    
    // Инициализация состояния
    function getInitialState() {
        // Проверяем наличие значения и приводим к boolean
        const savedState = localStorage.getItem('containerExpanded');
        return savedState ? savedState === 'true' : false;
    }

    // Применяем сохраненное состояние
    function applyState(isExpanded) {
        if (isExpanded) {
            contentContainer.classList.add('container-expanded');
            contentContainer.classList.remove('container-default');

             const containerClasses = [...contentContainer.classList].filter(className => 
                className.startsWith('max-container')
            );
                // Получить полное название класса
                if (containerClasses.length > 0) {
                    const fullClassName = containerClasses[0];            
                    // Можно извлечь число из класса
                    const match = fullClassName.match(/max-container-(\d+)/);
                    if (match) {
                        number = Number(match[1]) + 2;
                        contentContainer.classList.remove(containerClasses[0]);
                        contentContainer.classList.add('max-container-'+number);
                    }
                }
        } else {
            

            const containerClasses = [...contentContainer.classList].filter(className => 
                className.startsWith('max-container')
            );
            if(contentContainer.classList.contains('container-expanded')){
                // Получить полное название класса
                if (containerClasses.length > 0) {
                    const fullClassName = containerClasses[0];            
                    // Можно извлечь число из класса
                    const match = fullClassName.match(/max-container-(\d+)/);
                    if (match) {
                        number = Number(match[1]) - 2;
                        contentContainer.classList.remove(containerClasses[0]);
                        contentContainer.classList.add('max-container-'+number);
                    }
                }
            }
                
            contentContainer.classList.remove('container-expanded');
            contentContainer.classList.add('container-default');    
        }
    }

    // Инициализация при загрузке
    let isExpanded = getInitialState();
    applyState(isExpanded);

    // Обработчик клика
    resizeButton.addEventListener('click', function() {
        // Инвертируем текущее состояние
        isExpanded = !isExpanded;
        
        // Сохраняем новое состояние
        localStorage.setItem('containerExpanded', isExpanded.toString());
        
        // Применяем изменения
        applyState(isExpanded);
        
        console.log('State updated to:', isExpanded);
    });
});


</script>