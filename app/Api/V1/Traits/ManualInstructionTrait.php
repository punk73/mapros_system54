<?php

namespace App\Api\V1\Traits;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Http\Request;
use App\ManualInstruction;
use App\LineprocessManualInstruction;
use Illuminate\Database\QueryException;

trait ManualInstructionTrait {
    public function storeManualContent($data , $guid = null ) {
        $guidMaster = ($guid == null )? $this->getGuidMaster() : $guid;
        $content  = $data;

        if($content == null || $guidMaster == null) {
            return null; // ??
        }

        $manualInstruction = new ManualInstruction();
        $manualInstruction->guid_master = $guidMaster;
        $manualInstruction->content = $content;
        
        return $manualInstruction->save();
    }

    public function hasInstructionManual() {
        $parameter = isset($this->parameter)? $this->parameter : null;
        if($parameter == null) {
            return false;
        }

        $key = 'manual_content';

        if( isset($parameter[$key]) ) {
            if($parameter[$key] !== null ) {
                return true;
            }
        }

        return false;
    }

    public function checkInstructionManual($scannerIdParameter = null, $lineprocessIdParameter = null ) {
        $scannerId = (!is_null($scannerIdParameter))? $scannerIdParameter  : $this->getScannerId()['id'];
        $lineprocessId = (!is_null($lineprocessIdParameter))? $lineprocessIdParameter: $this->getLineprocess()['id'];
       
        try {
            //code...
            $model = LineprocessManualInstruction::where('scanner_id', $scannerId)
                ->where('lineprocess_id', $lineprocessId)
                ->where('has_check', 1)
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