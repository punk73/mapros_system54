@extends('voyager::master')


{{ menu('admin', 'bootstrap') }}

@section('content')
<div class="page-content browse container-fluid">
    @include('voyager::alerts')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-bordered">
                <div class="panel-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                            <th>Barcode</th>
                            <th>Input User</th>
                            <th>Input Date</th>
                            <th>Finish</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($data as $item)
                            <tr>
                            <td>{{$item->barcode}}</td>
                            <td>{{$item->input_user}}</td>
                            <td>{{$item->input_date}}</td>
                            <td style="color: {{$item->font_color}}" >{{$item->finish}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    {{$data}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection