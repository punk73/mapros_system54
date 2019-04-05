@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title', 'Copy Sequence' )

@section('page_header')
    <h1 class="page-title">
        <i class=""></i>
        Copy Sequence
    </h1>
    @include('voyager::multilingual.language-selector')
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-bordered">
                    <!-- form start -->
                    <form role="form"
                        class="form-edit-add"
                        action="{{ route('voyager.sequences.store') }}"
                        method="POST" enctype="multipart/form-data">

                        <!-- CSRF TOKEN -->
                        {{ csrf_field() }}

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
                            @foreach ($sequence as $key => $value)
                                 
                                <label for="name">{{ $key }}</label>

                                <div class="form-group">
                                    <input @if( !in_array($key, ['name', 'modelname']) ) readonly @endif  required type="text" class="form-control" name="{{$key}}"
                                    placeholder="{{$key}} ..."
                                    value="@if( !in_array($key, ['name', 'modelname']) ){{$value}}@endif">                           
                                </div>
                            @endforeach

                        </div><!-- panel-body -->

                        <div class="panel-footer">
                            <button type="submit" class="btn btn-primary save">{{ __('voyager::generic.save') }}</button>
                        </div>
                    </form>

                    <iframe id="form_target" name="form_target" style="display:none"></iframe>
                    

                </div>
            </div>
        </div>
    </div>

    
    <!-- End Delete File Modal -->
@stop

{{-- @section('javascript')
    <script>
        var params = {};
        var $image;

        $('document').ready(function () {
            $('.toggleswitch').bootstrapToggle();

            //Init datepicker for date fields if data-datepicker attribute defined
            //or if browser does not handle date inputs
            $('.form-group input[type=date]').each(function (idx, elt) {
                if (elt.type != 'date' || elt.hasAttribute('data-datepicker')) {
                    elt.type = 'text';
                    $(elt).datetimepicker($(elt).data('datepicker'));
                }
            });

            @if ($isModelTranslatable)
                $('.side-body').multilingual({"editing": true});
            @endif

            $('.side-body input[data-slug-origin]').each(function(i, el) {
                $(el).slugify();
            });

            $('.form-group').on('click', '.remove-multi-image', function (e) {
                e.preventDefault();
                $image = $(this).siblings('img');

                params = {
                    slug:   '{{ $dataType->slug }}',
                    image:  $image.data('image'),
                    id:     $image.data('id'),
                    field:  $image.parent().data('field-name'),
                    _token: '{{ csrf_token() }}'
                }

                $('.confirm_delete_name').text($image.data('image'));
                $('#confirm_delete_modal').modal('show');
            });

            $('#confirm_delete').on('click', function(){
                $.post('{{ route('voyager.media.remove') }}', params, function (response) {
                    if ( response
                        && response.data
                        && response.data.status
                        && response.data.status == 200 ) {

                        toastr.success(response.data.message);
                        $image.parent().fadeOut(300, function() { $(this).remove(); })
                    } else {
                        toastr.error("Error removing image.");
                    }
                });

                $('#confirm_delete_modal').modal('hide');
            });

            $('[data-toggle="tooltip"]').tooltip();

            /* make the value empty */
            $('#process_select').val([]);
            $('#process_select').trigger('change');            

            $("#process_select").on("select2:select", function (evt) {
                var element = evt.params.data.element;
                var $element = $(element);
                
                $element.detach();
                $(this).append($element);

                let val = $(this).val();
                if(val[0] == ""){
                    val.splice(0, 1)
                    console.log(val, 'inside')
                }
                let str = val.toString();
                let process = $('[name="process"]').val(str)
                console.log(val, str)

                $(this).trigger("change");
            });

            /* isi value process select jika process textbox sudah ada isinya */
            let processTextVal = $('[name="process"]').val();
            let data = processTextVal.split(',')
            /* the problem here is  */
            // console.log(data.length , processTextVal)

            if(processTextVal !== ""){
                /* get select2 options */
                var options = document.getElementById('process_select').options;
                /* 
                    make dictionary from those options 
                    the data will be :  opt = { 21: "smt", 25:"proses lain" } 
                */
                let opt = {}
                for (const option of options) {
                    opt[option.value] = option.text;               
                }

                for (let index = 0; index < data.length; index++) {
                    const value = data[index];
                    const text = opt[value];
                    /* make new option with specific text, value */
                    var newOption = new Option(text, value, true , true);
                    $('#process_select').append(newOption);
                }
                $('#process_select').trigger("change");
            }

            
        });
    </script>
@stop --}}
