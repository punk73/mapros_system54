{{-- @extends('layouts.app') --}}

{{-- @section('content') --}}
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                {{-- <div class="panel-heading">PRINT</div> --}}
                <div class="panel-body">
                    @foreach ($data as $item)
                        {{-- expr --}}
                        <div >
                            <table border="0" width="205px" cellspacing="0" cellpadding="0" style="font-family: Sans-serif !important; color: #383838 !important; font-weight: bold !important; margin-left: 0px !important; padding-top: 7px !important;">
                                <tr align="center" style="margin-top: 3px !important">
                                    <td height="25px">Dummy ID</td>
                                    <td rowspan="2" width="60px"> 
                                        <img style="max-height: 70px;" src="{{ asset( $item['img'])}}" /> 
                                    </td>
                                </tr>
                                <tr align="center" style="font-size: 15pt;">
                                    <td>{{$item['code']}}</td>
                                </tr>
                                <tr align="center" >
                                    <td colspan="2" height="25px" style="font-size: 8pt;">
                                        PT JVC ELECTRONICS INDONESIA
                                    </td>
                                </tr>
                            </table>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
{{-- @endsection --}}
