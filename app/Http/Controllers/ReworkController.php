<?php

namespace App\Http\Controllers;

use App\Master;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReworkController extends Controller
{
    public function index(Request $request)
    {
        $search = $request;

        $data = DB::table('rework')
            ->where(function($q) use ($request) {
                if($request->has(['key','filter','s'])) {
                    $filter = ($request->filter == 'contains') ? 'like' : '=';
                    $value = ($request->filter == 'contains') ? "%{$request->s}%" : $request->s;
                    $q->where($request->key, $filter, $value);
                }
            })
            ->orderBy('input_date', 'desc')
            ->where('modelnew', null )
            ->paginate();

        foreach ($data as $key => $rework) {
            # code...
            $finished = Master::where('serial_no', $rework->barcode)->first();
            $rework->finish = (!$finished) ? 'Not Yet' : 'Finished';
            $rework->font_color = (!$finished) ? 'red' : 'green';
        }

        // return $data;
        return view('vendor.voyager.rework.browse', \compact('data','search') );
    }
}
