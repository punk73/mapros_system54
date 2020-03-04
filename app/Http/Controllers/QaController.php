<?php

namespace App\Http\Controllers;

use App\Master;
use App\Scanner;
use Illuminate\Http\Request;
use DB;
use Exception;
use App\Doc_to;
use App\SerialNo;
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

    protected $colConfig = [];

    public function initColSetting($firstIndex) {
        return [
            $firstIndex,
            $firstIndex+1,
            $firstIndex+3,
            $firstIndex+4,
            $firstIndex+5,
        ];
    }

    public function __construct()
    {
        foreach ([1,8,15,22,29] as $firstIndex) {
            # code...
            $this->colConfig[] = $this->initColSetting($firstIndex);
        }
        # code...
        
        /* $colConfig = [
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
                17-1, //Q //change due to changing the template
                19-1, //S //change due to changing the template
                20-1, //T //change due to changing the template
                21-1  //U //change due to changing the template
            ], [
                24-1, //X //change due to changing the template
                26-1, //Z //change due to changing the template
                27-1, //AA //change due to changing the template
                28-1  //AB //change due to changing the template
            ],[
                31-1, //AE //change due to changing the template
                33-1, //AG //change due to changing the template
                34-1, //AH //change due to changing the template
                35-1  //AI //change due to changing the template
            ]
        ]; */
    }
    
    public function getFinishCount(Request $request) {
        $q = $this->getMainQuery($request);

		$rawQuery = $this->getEloquentSqlWithBindings($q);

		$data =  DB::select(DB::raw("select count(*) as aggregate from ({$rawQuery}) a"));

        if(count($data) > 0) {
            return $data[0]->aggregate;
        }else {
            return 0;
        }
    }

    public function getMainQuery(Request $request) {
        /* return DB::connection('mysql3')
            ->table('masters')
            ->select([
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
            ->where('a.judge', 'OK')
            ->where('b.modelname', $request->get('modelname'))
            ->where('b.lotno', $request->get('lotno'))
            ->distinct(); */
        
        $serialNumbers = SerialNo::
			select(['SERIAL_NO_ID'])
			->where('MODEL_NAME', $request->modelname )
			->where('PROD_NO', $request->lotno)
			->distinct()
			->get();
		
		$serialno = [];
		foreach($serialNumbers as $sn) {
			$serialno[] = trim( $sn->SERIAL_NO_ID);
		}

		return DB::connection('mysql3')
            ->table('masters')
            ->select([
                DB::raw('right(serial_no,8) as serial_no')
                , 'judge'
                , 'scan_nik'
                , 'created_at'
			])
            ->whereIn('serial_no', $serialno )
            ->where('scanner_id', $request->scanner_id )
            ->where('judge', 'OK')
            ->groupBy('serial_no')
            ->orderBy('serial_no', 'asc')
			->distinct();
    }

    public function download(Request $request) {
        ini_set('max_execution_time', 60*5); // 5 menit
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
            $sheetCounter = 1;
            $worksheet = $spreadsheet->getActiveSheet();

            $lineName = DB::connection('mysql3')
                ->table('scanners')
                ->select([
                    'lines.name'
                ])
                ->join('lines', 'line_id', '=', 'lines.id')
                ->where('scanners.id', $request->get('scanner_id') )
                ->first();

            $lotSize = (new Doc_to)->getLotSize($request->get('modelname'), $request->get('lotno'));
            $finishCount = $this->getFinishCount($request);

            if(!$lineName) {
                throw new Exception("Scanner with id {$request->scanner_id} not found.");
            } else {
                $lineName = $lineName->name;
            }

            $counter = 1;

            $data = $this->getMainQuery($request)
            ->chunk(50, function ($results) use (&$spreadsheet, &$worksheet, &$chunkCounter, &$sheetCounter, $request, $lineName, &$counter, $lotSize, $finishCount ){
                $rowCount = 9; //start from 
                foreach($results as $key => $result) {
                    $colCount = 1;
                    $colConfig = $this->colConfig;
                    // loop over the query result the get the column name and column value;
                    foreach ($result as $key => $colValue) {
                        # code...
                        // render counter nya;
                        $worksheet->setCellValueByColumnAndRow( $colConfig[$chunkCounter][0], $rowCount, $counter );
                        // render the data
                        $worksheet->setCellValueByColumnAndRow( $colConfig[$chunkCounter][$colCount], $rowCount, $colValue );
                        $colCount++;
                    }
                    $rowCount++;
                    $counter++;
                }
                $chunkCounter++;

                if($chunkCounter > 4) {
                    // assign header data :
                    $worksheet->setCellValueByColumnAndRow(4,2, $request->get('modelname') );
                    $worksheet->setCellValueByColumnAndRow(4,3, $lineName );
                    $worksheet->setCellValueByColumnAndRow(4,4, $request->get('lotno') );
                    $remarks = "Finish {$finishCount} / {$lotSize}";
                    $worksheet->getCell('Q3')->setValue($remarks);
                    // we can reset $chunkCounter here
                    $chunkCounter = 0;
                    $sheetCounter++;
                    // then,  we need to copy from sheet one to a new sheet
                    $clonedWorksheet = clone $spreadsheet->getSheetByName('blank'); //get sheet blank
                    $newTitle = trim("Sheet ({$sheetCounter})") ;
                    $clonedWorksheet->setTitle($newTitle);
                    $worksheet = $spreadsheet->addSheet($clonedWorksheet);
                    // return false;
                }
            });

            
            // assign header data for the last worksheet:
            // it is necessary because sometimes, chunkcounter not reach 4 then the looping is finish;
            $worksheet->setCellValueByColumnAndRow(4,2, $request->get('modelname') );
            $worksheet->setCellValueByColumnAndRow(4,3, $lineName );
            $worksheet->setCellValueByColumnAndRow(4,4, $request->get('lotno') );
            
            $remarks = "Finish {$finishCount} / {$lotSize}";
            $worksheet->getCell('Q3')->setValue($remarks);


            $spreadsheet->removeSheetByIndex($spreadsheet->getIndex(
                $spreadsheet->getSheetByName('blank')
            )); //delete blank sheet

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
