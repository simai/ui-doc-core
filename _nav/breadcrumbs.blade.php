@php
    /*$navigation = $page->getNavItems();*/
    //print_r($page->getBreadcrumbsItems());
@endphp

<section>
    @php
    //echo "<pre>";
         $breadcrumbs_array = $page->generateBreadcrumbs();
         //print_r($breadcrumbs_array);
    //echo "</pre>";
    $main_page = false;
    @endphp
    <ul class="sf-breadcrumb">
          

    @foreach($breadcrumbs_array as $key => $item)
      @if($item['title'] === 'Главная' && $main_page !== true)
        <li class="sf-breadcrumb-item">
            <a href="#" >
              <i class="color-on-surface sf-icon sf-icon-medium">home</i>
            </a>
            <i class="sf-icon sf-icon-light">chevron_right</i>
          </li>
          @php
            $main_page = true;
          @endphp
      @else
        <li class="sf-breadcrumb-item text-1/2 ">
          <a href="{{$item['url']}}">{{$item['title']}}</a>
            @if(!$loop->last)
              <i class="sf-icon sf-icon-light">chevron_right</i>
            @endif
        </li>
      @endif
        
    @endforeach
    </ul>
</section>
