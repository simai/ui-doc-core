@extends('_core._layouts.master')


@section('body')
    <section>

        @include('_core._nav.breadcrumbs')

        <div class="flex flex-col lg:flex-row">
            <div class="main--content" v-pre>
                @yield('content')
            </div>
        </div>
    </section>
    @include('_core._nav.bottom-nav')
@endsection
