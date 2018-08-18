@extends('layouts.app')

@section('content')
<style type="text/css">
    /* The image used */
    body{
        background-image: url({{url("/storage/wellcome.jpg")}});
    }
</style>

<div id="app">
    {{-- <mymain></mymain> --}}
    <router-view></router-view>
</div>


@endsection
