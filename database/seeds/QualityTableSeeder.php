<?php

use Illuminate\Database\Seeder;

class QualityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->insertDataTypes();

        $dataType = \DB::table('data_types')->where($this->getDatatype())->orderBy('id','desc')->first();

        if($dataType) {
            $id = $dataType->id;
            $data = $this->getDataRow();
            foreach ($data as $key => $value) {
                # code...
                $newDataRows = [
                    'data_type_id' => $id, 
                    'field' => $value, 
                    'type' => 'text', 
                    'display_name' => $value, 
                    'required'=> 1, 
                    'browse' => ($value == 'ID') ? 0: 1, 
                    'read'   => ($value == 'ID') ? 0: 1, 
                    'edit'   => ($value == 'ID') ? 0: 1, 
                    'add'    => ($value == 'ID') ? 0: 1, 
                    'delete' => ($value == 'ID') ? 0: 1, 
                    'details' => null, 
                    'order' => $key + 1
                ];

                \DB::table('data_rows')->insert($newDataRows);
            }

        }

    }

    protected function insertDataTypes() {
        $datatype = $this->getDataType();

        $exists = \DB::table('data_types')->where($datatype)->first();

        $result = true;
        if(!$exists) {
            $result = \DB::table('data_types')->insert($datatype);
        }

        return $result;
    }

    protected function getDatatype() {
        return [ 
            'name' => 'QUALITY',
            'slug' => 'quality',
            'display_name_singular' => 'Quality',
            'display_name_plural' => 'Qualities',
            'icon' => 'voyager-list',
            'model_name' => 'App\\Quality',
            'policy_name' => NULL,
            'controller' => '',
            'description' => '',
            'generate_permissions' => 1,
            'server_side' => 1,
            'details' => NULL
        ];
    }

    protected function getDataRow () {
        return [
            'ID_QUALITY' 
            ,'MODEL'     
            ,'BOARD'     
            ,'PCB_ID_NEW'
            ,'PCB_ID_OLD'
            ,'GUIDMASTER'
            ,'APPROVED' 
        ];
    }
}
