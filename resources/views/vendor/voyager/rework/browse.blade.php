@extends('voyager::master')


{{ menu('admin', 'bootstrap') }}

@section('content')
<div class="page-content browse container-fluid">
    @include('voyager::alerts')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-bordered">
                <div class="panel-body">
                    <ul>
                        @foreach ($data as $item)
                            <li>{{$item}}</li>
                        @endforeach
                    </ul>
                    {{$data}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection