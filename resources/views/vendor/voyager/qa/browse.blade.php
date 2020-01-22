@extends('voyager::master')


{{ menu('admin', 'bootstrap') }}

@section('content')
<div class="page-content browse container-fluid">
    @include('voyager::alerts')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-bordered">
                <div class="panel-body">
                    <form method="GET" action="./qa/download">

                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1">Model</span>
                            <input type="text" class="form-control" name="modelname"  value="{{$request->get('modelname')}}" placeholder="Model" aria-describedby="basic-addon1">
                        </div>
                        <br>
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon2">Lot</span>
                            <input type="text" class="form-control" name="lotno" value="{{$request->get('lotno')}}" placeholder="lot" aria-describedby="basic-addon2">
                        </div>
                        <br>
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon3">Scanner</span>
                            <select name="scanner_id" class="form-control" id="scanner_id">
                                @foreach ($scanners as $scanner)    
                                <option value="{{$scanner['id']}}">{{$scanner['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <br>
                        <button class="btn btn-success">Search</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-bordered">
                <div class="panel-body">

                    <br>

                    Params : {{ json_encode($request)}}
                </div>
            </div>
        </div>
    </div>

@endsection