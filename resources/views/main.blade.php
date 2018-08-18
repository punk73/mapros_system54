@extends('layouts.app')

@section('content')
<style type="text/css">
    /* The image used */
    body{
        background-image: url({{url("/storage/wellcome.jpg")}});
    }
</style>

{{-- <div class="container">
    <div class="row">
        @if (isset($error) && $error != null )        
            <div class="col-md-8 col-md-offset-2">
                <div class="alert alert-danger alert-dismissible show" role="alert">
                  {{ $error['message'] }}
                  <button class="close" data-dismiss="alert" aria-label="close">
                      <span aria-hidden="true">&times;</span>
                  </button>
                </div>
            </div>
        @endif

        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">MAIN</div>
                <div class="panel-body">
                    @include('voyager::alerts')

                    <form class="form-horizontal" role="form" method="POST" action="{{ url('main') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('nik') ? ' has-error' : '' }}">
                            <label for="nik" class="col-md-4 control-label">NIK</label>

                            <div class="col-md-6">
                                <input id="nik" type="text" maxlength="6" class="form-control" name="nik" value="{{ old('nik') }}" required autofocus>

                                @if ($errors->has('nik'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('nik') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('ip_address') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">IP Address</label>

                            <div class="col-md-6">
                                <input id="ip_address" type="text" class="form-control" name="ip_address" value="{{ old('ip_address') }}" required autofocus>

                                @if ($errors->has('ip_address'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('ip_address') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('board_id') ? ' has-error' : '' }}">
                            <label for="board_id" class="col-md-4 control-label">Board Id</label>

                            <div class="col-md-6">
                                <input id="board_id" type="board_id" class="form-control" name="board_id" value="{{ old('email') }}" required>

                                @if ($errors->has('board_id'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('board_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>


                </div>
            </div>
        </div>
    </div>
</div> --}}

<div id="app">
    
</div>

@endsection
