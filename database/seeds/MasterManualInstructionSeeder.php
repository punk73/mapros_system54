<?php

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Permission;
use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Models\MenuItem;

class MasterManualInstructionSeeder extends Seeder
{
    public function run()
    {
        $this->insertDataTypes();

        $dataType = \DB::table('data_types')->where($this->getDatatype())->orderBy('id','desc')->first();

        if($dataType !== null ) {
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
                    'browse' => ($value == 'id') ? 0: 1, 
                    'read'   => ($value == 'id') ? 0: 1, 
                    'edit'   => ($value == 'id') ? 0: 1, 
                    'add'    => ($value == 'id') ? 0: 1, 
                    'delete' => ($value == 'id') ? 0: 1, 
                    'details' => NULL, 
                    'order' => $key + 1
                ];

                $dataRow = new DataRow();
                $dataRow->firstOrCreate($newDataRows);
            }

        }

        $this->insertPermissions();

        $this->insertMenuItem();

    }

    protected function insertMenuItem($menuId = 1) {
        $menuItem = new MenuItem();

        $order = MenuItem::select(['order'])->orderBy('order', 'desc')->first();

        $menuItem->firstOrCreate([
            'menu_id' => $menuId,
            'title' => 'Master Manual Instruction',
            'url' => 'admin/master-manual-instruction',
            'target' => '_self',	
            'icon_class' => 'voyager-barbell',
            'color' => '#000000',
        ], [
            'order' => $order['order'] + 1
        ]);
    }

    protected function insertPermissions(){
        $actions = [
            'delete',
            'add',
            'edit',
            'read',
            'browse'
        ];

        $tableName = 'master_manual_instructions';

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
            'name' => 'master_manual_instructions',
            'slug' => 'master-manual-instruction',
            'display_name_singular' => 'Master Manual Instruction',
            'display_name_plural' => 'Master Manual Instructions',
            'icon' => 'voyager-list',
            'model_name' => 'App\\MasterManualInstruction',
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
            "id",
            "content",
            "modelname",
        ];
    }
}
