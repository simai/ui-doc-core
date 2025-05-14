@extends('_core._layouts.master')

@section('nav-toggle')
    @include('_core._nav.menu-toggle')
@endsection

@section('body')
    <section>
        <div class="flex flex-col lg:flex-row">

            <div class="main--content" v-pre>
                @yield('content')
            </div>
        </div>
    </section>
    @include('_core._nav.bottom-nav')
@endsection
