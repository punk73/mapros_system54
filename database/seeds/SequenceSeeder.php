<?php

use Illuminate\Database\Seeder;
use App\Sequence;
use App\Mastermodel;

class SequenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){

        $sequences = [
            [
                'name' => 'model',
                'modelname' => 'model',
                'pwbname' => 'main',
                'line_id' => 1,
                'process' => '2,3,4,5',
            ],
            [
                'name' => 'model',
                'modelname' => 'model',
                'pwbname' => 'panel',
                'line_id' => 1,
                'process' => '6,7,8,2',
            ],

        ];

        foreach ($sequences as $key => $data) {
            $sequence = new Sequence;
            $sequence->name = $data['name'];
            $sequence->modelname = $data['modelname'];
            $sequence->pwbname = $data['pwbname'];
            $sequence->line_id = $data['line_id'];
            $sequence->process = $data['process'];
            $sequence->save();
        }
    }

    public function runBackup()
    {   
        $sequences = $this->getData();
        foreach ($sequences as $key => $data) {
            # code...
            $sequence = new Sequence;
            $sequence->name = $data['name'];
            $sequence->modelname = $data['modelname'];
            $sequence->pwbname = $data['pwbname'];
            $sequence->line_id = $data['line_id'];
            $sequence->process = $data['process'];
            $sequence->save();
        }

    }

    public function getData(){
        // name    line_id process
        $Mastermodel = Mastermodel::take(20)->get();;

        $process = [
            ['PNL',     1,   '13,14,15,16,17,20'],
            ['MST',     1,   '18,19,20,27,35,28,29,30,31,32,33,36,37,38,39,40'],
            ['MAIN',    1,   '1,3,4,7,9,10,12,18'],
            ['Daughter',1,   '1,5,7,18'],
            ['Switch',  1,   '1,5,7,8,13'],
        ];


        $data = [];
        foreach ($Mastermodel as $key => $model) {
            $item = [];
            $item['name']       = $model['name'];;
            $item['modelname']  = $model['name'];
            $item['pwbname']    = $model['pwbname'];
            $item['process']    = $process[rand(0,4)][2];
            $item['line_id']    = 1;
            $data[] = $item;
        }

        return $data;

    }
}
