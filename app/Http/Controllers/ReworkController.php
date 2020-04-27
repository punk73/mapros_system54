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
            ->select(['rework.*'])
            ->leftJoin('masters', DB::raw('masters.serial_no COLLATE utf8_unicode_ci'), '=', DB::raw('rework.barcode COLLATE utf8_unicode_ci'))
            ->paginate();

        return view('vendor.voyager.rework.browse', \compact('data') );
    }
}
