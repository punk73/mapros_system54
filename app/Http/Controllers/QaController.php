<?php

namespace App\Http\Controllers;

use App\Master;
use App\Scanner;
use Illuminate\Http\Request;
use DB;
class QaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $scanners = Scanner::select(['id', 'name'])
            ->where('name', 'like', '%QA%')
            ->get();

        return view('vendor.voyager.qa.browse', ['scanners' => $scanners, 'request' => $request ]);
    }

    public function download(Request $request) {
        
        /* 
            SELECT DISTINCT right(a.serial_no,8), a.judge, a.scan_nik, a.created_at,b.modelname, b.lotno FROM mapros.masters a
            left join boards b on a.guid_master = b.guid_master 
            where a.serial_no like 'DPXGT711RA9N%'
            AND a.STATUS = 'OUT'
            and a.scanner_id = '36'
            and b.modelname = 'DPXGT711RA9N' 
            and b.lotno = '016A'
            ORDER BY a.SERIAL_NO ASC;
        */

        $data = null;
        if($request->has('modelname') && $request->has('lotno') && $request->has('scanner_id')) {
            $data = Master::select([
                // DB::raw('right(a.serial_no,8) as serial_no')
                'serial_no'
                , 'a.judge'
                , 'a.scan_nik'
                , 'a.created_at'
                , 'b.modelname'
                , 'b.lotno'

            ])->from('masters as a')
            ->join('boards as b', 'a.guid_master', '=', 'b.guid_master')
            ->where('a.serial_no', 'like', $request->get('modelname') .'%' )
            ->where('a.scanner_id', $request->get('scanner_id'))
            ->where('a.status', 'OUT')
            ->where('b.modelname', $request->get('modelname'))
            ->where('b.lotno', $request->get('lotno'))
            ->orderBy('a.serial_no', 'asc')
            ->get();
        }

        return $data;

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
