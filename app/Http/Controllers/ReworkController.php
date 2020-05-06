<?php

namespace App\Http\Controllers;

use App\Master;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReworkController extends Controller
{
    public function index(Request $request)
    {
        // $data = Master::select([
        //     'guid_master','serial_no'
        // ])->distinct()
        // ->paginate();

        $data = DB::table('rework')
            // ->select(['rework.*'])
            // ->leftJoin('masters', DB::raw('masters.serial_no COLLATE utf8_unicode_ci'), '=', DB::raw('rework.barcode COLLATE utf8_unicode_ci'))
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
        return view('vendor.voyager.rework.browse', \compact('data') );
    }
}
