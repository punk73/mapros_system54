<?php

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Permission;

class InnerLabelSeeder extends Seeder
{
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

        $this->insertPermissions();

    }

    protected function insertPermissions(){
        $actions = [
            'delete',
            'add',
            'edit',
            'read',
            'browse'
        ];

        $tableName = 'INNERLABEL';

        foreach ($actions as $key => $action) {
            $data = $action . '_' . $tableName;
            $isExists = \DB::table('permissions')->where([
                'key' => $data,
                'table_name' => $tableName,
            ])->first();

            // kalau belum ada, insert
            if(!$isExists) {
                // \DB::table('permissions')->insert();

                $permission = new Permission([
                    'key' => $data,
                    'table_name' => $tableName,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                $permission->save();
                
                $roleId = 1; //admin
                $permission->roles()->attach($roleId);
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
            'name' => 'INNERLABEL',
            'slug' => 'inner-label',
            'display_name_singular' => 'Inner Label',
            'display_name_plural' => 'Inner Labels',
            'icon' => 'voyager-list',
            'model_name' => 'App\\Innerlabel',
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
            "ID",
            "MODEL_NAME",
            "MODELNUMBER",
            "Y_NUMBER"
        ];
    }
}
