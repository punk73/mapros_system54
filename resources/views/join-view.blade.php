@extends('layouts.app')

@section('content')
<style type="text/css">
    /* The image used */
    body{
        background-image: url({{url("/storage/wellcome.jpg")}});
    }
</style>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">JOIN</div>
                <div class="panel-body">
                    @include('voyager::alerts')

                    <form class="form-horizontal" role="form" method="POST" action="{{ url('join') }}">
                        {{ csrf_field() }}

                        @foreach ($response->keys() as $key)
                            <div hidden="true"  class="form-group{{ $errors->has($response->get($key)[0] ) ? ' has-error' : '' }}">
                                <label for="{{$key}}" class="col-md-4 control-label">{{ $key  }}</label>

                                <div class="col-md-6">
                                    <input id="{{$key}}" type="hidden" class="form-control" name="{{$key}}" value="{{ $response->get($key)[0] }}"  required>

                                    @if ($errors->has($key))
                                        <span class="help-block">
                                            <strong>{{ $errors->first($key) }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>                            
                        @endforeach

                        <div class="form-group{{ $errors->has('board_id') ? ' has-error' : '' }}">
                            <label for="board_id" class="col-md-4 control-label">Board Id</label>

                            <div class="col-md-6">
                                <input id="board_id" type="board_id" class="form-control" name="board_id" value="{{ old('board_id') }}" required>

                                @if ($errors->has('board_id'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('board_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        {{-- <div class="well">{{ $response }}</div> --}}
                        

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
</div>
@endsection
