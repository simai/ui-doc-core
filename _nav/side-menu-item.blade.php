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
    <!--<li class = "sf-side-menu-list-item sf-side-menu-list-item--active">
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
    </li>-->
</ul>

<div class="table-of-contents">

    </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const headings = document.querySelector('main').querySelectorAll('h1, h2, h3, h4, h5, h6');
    const tocList = document.querySelector('.sf-side-menu-list');

    headings.forEach(heading => {
        if (!heading.id) {
            heading.id = heading.textContent.toLowerCase().replace(/\s+/g, '-');
        }
        const listItem = document.createElement('li');
        listItem.className = `sf-side-menu-list-item`;
        listItem.innerHTML = `<a href="#${heading.id}">${heading.textContent}</a>`;
        tocList.appendChild(listItem);
    });

    [...document.querySelectorAll('.sf-side-menu-list-item')].forEach(element => {
        element.addEventListener('click', function() {
            [...document.querySelectorAll('.sf-side-menu-list-item')].forEach(li => li.classList.remove('sf-side-menu-list-item--active'));
            element.classList.add('sf-side-menu-list-item--active');
        });
    });
});

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