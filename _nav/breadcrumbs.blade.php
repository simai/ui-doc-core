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
    @endphp
    <ul class="sf-breadcrumb">
          <li class="sf-breadcrumb-item">
            <a href="#" >
              <i class="color-primary sf-icon sf-icon-light">home</i>
            </a>
          </li>

    @foreach($breadcrumbs_array as $key => $item)
        <li class="sf-breadcrumb-item text-1/2 ">
          <a href="{{$item['url']}}">{{$item['title']}}</a>
            <i class="sf-icon sf-icon-light">chevron_right</i>
          </li>
    @endforeach
    </ul>
</section>
