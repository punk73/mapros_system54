@extends('voyager::master')


{{ menu('admin', 'bootstrap') }}

@section('content')
<div class="page-content browse container-fluid">
    @include('voyager::alerts')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-bordered">
                <div class="panel-body">
                    {{-- start --}}
                    <form method="get" class="form-search">
                        <div id="search-input">
                            <select id="search_key" name="key" tabindex="-1"  aria-hidden="true">
                                <option value="barcode" @if($search->filter == "barcode"){{ 'selected' }}@endif>Barcode</option>
                                <option value="input_user" @if($search->filter == "input_user"){{ 'selected' }}@endif>Input User</option>
                            </select>
                            
                            <select id="filter" name="filter" tabindex="-1"  aria-hidden="true">
                                <option value="contains" @if($search->filter == "contains"){{ 'selected' }}@endif>contains</option>
                                <option value="equals" @if($search->filter == "equals"){{ 'selected' }}@endif>=</option>
                            </select>
                            
                            <div class="input-group col-md-12">
                                <input type="text" class="form-control" placeholder="Search" name="s" 
                                value="@if(isset($search->s)){{$search->s}}@endif">
                                <span class="input-group-btn">
                                    <button class="btn btn-info btn-lg" type="submit">
                                        <i class="voyager-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </form>

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

@section('javascript')
    <!-- DataTables -->
    
    <script>
        $(document).ready(function () {
             
            // $('#search-input select').select2({
            //     // minimumResultsForSearch: Infinity
            // }); 

            // $('select').select2({});

             
            // $('.select_all').on('click', function(e) {
            //     $('input[name="row_id"]').prop('checked', $(this).prop('checked'));
            // });
        });
    </script>
@stop