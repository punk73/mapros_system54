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
                            <input required type="text" class="form-control" name="modelname"  value="{{$request->get('modelname')}}" placeholder="Model" aria-describedby="basic-addon1">
                        </div>
                        <br>
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon2">Lot</span>
                            <input required type="text" class="form-control" name="lotno" value="{{$request->get('lotno')}}" placeholder="lot" aria-describedby="basic-addon2">
                        </div>
                        <br>
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon3">Scanner</span>
                            <select required name="scanner_id" class="form-control" id="scanner_id">
                                <option disabled selected value="">-- Please select one of these --</option>
                                @foreach ($scanners as $scanner)    
                                <option value="{{$scanner['id']}}">{{$scanner['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <br>
                        <div class="input-group">
                            <span class="input-group-addon">Process Name</span>
                            <input required type="text" class="form-control" id="process_name" name="process_name" placeholder="process name" aria-describedby="basic-addon2">    
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
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection

@section('javascript')
<script>
    $(document).ready(function(){
        $('#scanner_id').on('change', function(e) {
            let selectedText = $('#scanner_id option:selected').html();
            let result = "Appearance Check";
            // console.log(selectedText);
            if(selectedText.includes('QA 1') == false){
                result = "Inspection Check";
            }

            $('#process_name').val(result);
        })
    })
</script>
@endsection