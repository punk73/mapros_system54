<?php

namespace App\Api\V1\Traits;
use App\Carton;
use App\LineprocessCarton;

trait CartonTrait {
    
    public function storeCarton($data , $guid = null ) {
        $guidMaster = ($guid == null )? $this->getGuidMaster() : $guid;
        $content  = $data;

        if($content == null || $guidMaster == null) {
            return null; // ??
        }

        $carton = new Carton();
        $carton->guid_master = $guidMaster;
        $carton->content = $content;
        
        return $carton->save();
    }

    public function hasCarton() {
        $parameter = isset($this->parameter)? $this->parameter : null;
        if($parameter == null) {
            return false;
        }

        $key = 'carton';

        if( isset($parameter[$key]) ) {
            if($parameter[$key] !== null ) {
                return true;
            }
        }

        return false;
    }

    public function checkCarton($scannerIdParameter = null, $lineprocessIdParameter = null, $modelnameParameter = null ) {
        $scannerId = (!is_null($scannerIdParameter))? $scannerIdParameter  : $this->getScanner()['id'];
        $lineprocessId = (!is_null($lineprocessIdParameter))? $lineprocessIdParameter: $this->getLineprocess()['id'];
        $modelname = (!is_null($modelnameParameter)) ? $modelnameParameter:$this->getModelname();
        try {
            //code...
            $model = LineprocessCarton::where('scanner_id', $scannerId)
                ->where('lineprocess_id', $lineprocessId)
                ->where('has_check', 1)
                ->where('modelname', $modelname)
                ->first();

            /* ketika tidak ketemu */
            if(!$model) {
                return false;
            }else {
                return true;
            }
        } catch ( QueryException $th) {
            return false;
        }
        
    }
}