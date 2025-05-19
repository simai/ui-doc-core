@php
    /*$navigation = $page->getNavItems();*/
    $breadcrumbs_array = explode("/", $page->getPath());
    //print_r($page->getBreadcrumbsItems());
@endphp

<section>
    @php 
    echo "<pre>";
         $breadcrumbs_array = generateBreadcrumbs($page);
         //print_r($breadcrumbs_array);
    echo "</pre>";
    @endphp
    <ul class="sf-breadcrumb">
          <li class="sf-breadcrumb-item">
            <a href="#" class="text-1/2">
              <i class="color-primary sf-icon sf-icon-light sf-breadcrumb--primary-color">home</i>  
            </a>
          </li>
          
    @foreach($breadcrumbs_array as $key => $item)
        <li class="sf-breadcrumb-item text-1/2 sf-breadcrumb--primary-color">
          <a href="#" class="text-1/2">{{$item['title']}}</a>
            <i class="sf-icon sf-icon-light sf-breadcrumb--primary-color">chevron_right</i>
          </li>
    @endforeach
    
          <!--<li class="sf-breadcrumb-item">
            <a href="#" class="text-1/2">Главная</a>
            <i class="sf-icon sf-icon-light">chevron_right</i>
          </li>
          <li class="sf-breadcrumb-item" aria-current="page">
            <a href="#" class="text-1/2">...</a>
            <i class="sf-icon sf-icon-light sf-breadcrumb--primary-color">chevron_right</i>
          </li>
          <li class="sf-breadcrumb-item sf-breadcrumb--primary-color" aria-current="page">
            <a href="#" class="text-1/2">Главная</a>
          </li>-->
    </ul>
</section>
