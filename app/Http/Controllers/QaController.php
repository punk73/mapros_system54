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

    public function getWorksheet() {
        $template = storage_path('app\public\temp.xlsx'); //url('report/FORM_CAR.xlsx');
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($template);
        $reader->setIncludeCharts(true);
        $spreadsheet = $reader->load($template);

        $worksheet = $spreadsheet->getWorksheet();

        return $worksheet;
    }

    protected $colConfig = [
        [
            2, //B
            4, //D
            5, //E
            6  //F 
        ], [
            9, //I
            11, //K
            12, //L
            13  //M
        ], [
            17, //Q
            19, //S
            20, //T
            21  //U
        ], [
            24, //X
            26, //Z
            27, //AA
            28  //AB
        ],[
            31, //AE
            33, //AG
            34, //AH
            35  //AI
        ]
    ];

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

        $this->validate($request, [
            'modelname' => 'required',
            'lotno' => 'required',
            'scanner_id' => 'required',
        ]);

        $data = null;
        if($request->has('modelname') && $request->has('lotno') && $request->has('scanner_id')) {
            
            $template = storage_path('app\public\report_template.xlsx'); //url('report/FORM_CAR.xlsx');
            $fileName = 'QA-Reports';
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($template);
            $reader->setIncludeCharts(true);
            $spreadsheet = $reader->load($template);

            $chunkCounter = 0;
            $worksheet = $spreadsheet->getActiveSheet();

            $data = Master::select([
                DB::raw('right(a.serial_no,8) as serial_no')
                , 'a.judge'
                , 'a.scan_nik'
                , 'a.created_at'
                // , 'b.modelname'
                // , 'b.lotno'
            ])->from('masters as a')
            ->join('boards as b', 'a.guid_master', '=', 'b.guid_master')
            ->where('a.serial_no', 'like', $request->get('modelname') .'%' )
            ->where('a.scanner_id', $request->get('scanner_id'))
            ->where('a.status', 'OUT')
            ->where('b.modelname', $request->get('modelname'))
            ->where('b.lotno', $request->get('lotno'))
            ->distinct()
            ->orderBy('a.serial_no', 'asc')
            ->chunk(50, function ($results) use ($worksheet, &$chunkCounter){
                $rowCount = 9; //start from 
                foreach($results as $key => $result) {
                    $colCount = 0;
                    $colConfig = $this->colConfig;
                    // loop over the query result the get the column name and column value;
                    foreach ($result->toArray() as $key => $colValue) {
                        # code...
                        $worksheet->setCellValueByColumnAndRow( $colConfig[$chunkCounter][$colCount], $rowCount, $colValue );
                        $colCount++;
                    }
                    $rowCount++;
                }
                $chunkCounter++;

                if($chunkCounter > 4) {
                    // we can reset $chunkCounter here
                    return false;
                }
            });

            // return $tmpResult;
            // Redirect output to a client’s web browser (Xls)
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$fileName.'.xlsx"');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
        }

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
