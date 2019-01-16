<?php

use Illuminate\Database\Seeder;
use App\Lineprocess;
use App\LineprocessInspect;

class LineprocessInspectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datas = Lineprocess::select([
            'lineprocesses.id',
            'scanners.id as scanner_id'
        ])
        ->whereIn('lineprocesses.name', $this->getData() )
        ->leftJoin('scanners', function ($q){
            $q->on('lineprocesses.id', '=', 'scanners.lineprocess_id')
            ->where('scanners.line_id', 2 );
        })
        ->get();

        foreach ($datas as $key => $data ) {
            $newData = new LineprocessInspect;
            $newData->lineprocess_id = $data['id'];
            $newData->scanner_id = $data['scanner_id'];
            $newData->has_log = 1;
            $newData->save();
        }

    }

    private function getData(){
        return [ 
            'Inspect 1',
            'Inspect 2',
            'Inspect 3',
            'Inspect 4',
            'Inspect 5',
            'Inspect 6',
            'Inspect 7',
            'Inspect 8',
        ];
    }
}
