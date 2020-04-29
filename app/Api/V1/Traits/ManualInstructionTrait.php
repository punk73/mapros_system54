<?php

namespace App\Api\V1\Traits;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Http\Request;
use App\ManualInstruction;
use App\LineprocessManualInstruction;
use Illuminate\Database\QueryException;
use App\MasterManualInstruction;

trait ManualInstructionTrait {
    public function storeManualContent($data , $guid = null ) {
        $guidMaster = ($guid == null )? $this->getGuidMaster() : $guid;
        $content  = $data;

        if($content == null || $guidMaster == null) {
            return null; // ??
        }

        // diseragamkan saja, semuanya jadi array instead of string;
        if(!is_array($content)) {
            // swap the value
            $temp = [];
            $temp[] = $content;
            $content = $temp;
        }

        foreach ($content as $key => $contentValue ) {
            if($contentValue == null || $contentValue == "") {
                continue;
            }
            
            $manualInstruction = new ManualInstruction();
            $manualInstruction->guid_master = $guidMaster;
            $manualInstruction->content = $contentValue;
            $result = $manualInstruction->save();

            if(!$result){
                return false;
            }
        }

        return true;
    }

    // we need to repair CompareModelname method
    /* 
        1. get contents of array.
        2. get mastermodel with specific modelname
        3. compare that with contents.
        4. make sure each mastermodel has the same content
    */

    public function CompareModelArray($contents, $modelname = null) {
        if ($modelname == null && method_exists($this, 'getModelname')) {
            $modelname = $this->getModelname();
        }

        $contentHashMap = [];
        foreach ($contents as $key => $content) {
            $contentHashMap[$content] = $content;
        }

        $masters = MasterManualInstruction::where('modelname', $modelname )->get();

        foreach ($masters as $key => $master) {
            # code...
            $masterContent = $master->content;
            $found = isset($contentHashMap[$masterContent]);
            if($found) {
                // remove masterContent from $contentHashMap to checklist that content;
                unset($contentHashMap[$masterContent]);
            }else{
                return false;
            }
        }

        return true;
    }
 
    public function CompareModelname($contents, $modelname = null ) {
        /* check if getmodelname exists */
        if(is_array($contents)){ 
            # how to handle two manual instruction that scan the same content;
            return $this->CompareModelArray($contents, $modelname);
        }else {
            return $this->checkModelname($contents, $modelname);
        }
    }

    public function checkModelname($content, $modelname = null) {
        if ($modelname == null && method_exists($this, 'getModelname')) {
            $modelname = $this->getModelname();
        }

        return MasterManualInstruction::where([
            'content' => $content,
            'modelname' => $modelname
        ])->exists();
    }

    public function hasMasterManualInstruction($modelname = null) {
        if($modelname == null && method_exists($this, 'getModelname') ) {
            $modelname = $this->getModelname();
        }

        return MasterManualInstruction::where([
            'modelname' => $modelname
        ])->exists();
    }

    public function hasInstructionManual() {
        $parameter = isset($this->parameter)? $this->parameter : null;
        if($parameter == null) {
            return false;
        }

        $key = 'manual_content';

        if( isset($parameter[$key]) ) {
            if($parameter[$key] !== null ) {
                if(is_array($parameter[$key])) {
                    return count($parameter[$key]) > 0;
                    // jika array ada isinya, tapi empty string, maka haruskah return false ??

                }

                return true;
            }
        }

        return false;
    }

    public function checkInstructionManual($scannerIdParameter = null, $lineprocessIdParameter = null ) {
        $scannerId = (!is_null($scannerIdParameter))? $scannerIdParameter  : $this->getScanner()['id'];
        $lineprocessId = (!is_null($lineprocessIdParameter))? $lineprocessIdParameter: $this->getLineprocess()['id'];
       
        try {
            //check apakah harus scan instruction manual disini.
            $model = LineprocessManualInstruction::where('scanner_id', $scannerId)
                ->where('lineprocess_id', $lineprocessId)
                ->where('has_check', 1)
                ->first();

            /* ketika tidak ketemu */
            if(!$model) {
                return false;
            }else {
                /* cek apakah master manual instruction sudah disetting ketika menentukan apakah perlu mengevalusi manual instruction */
                $hasMasterManualInstructionSetting = $this->hasMasterManualInstruction();
                return ( true && $hasMasterManualInstructionSetting );
            }
        } catch ( QueryException $th) {
            return false;
        }
        
    }

    public function checkContentWithModelname($contents){
        if(is_array( $contents)){
            foreach ($contents as $key => $content) {
                # code...
                $currentModel = (\method_exists($this, 'getModelname')) ? $this->getModelname() : 'unknown';
                $masterContent = MasterManualInstruction::select(['content', 'modelname'])
                ->where('modelname', $currentModel)
                ->get();
                
                throw new StoreResourceFailedException("TOLONG PASTIKAN MANUAL INSTRUCTION SESUAI MODELNYA. CLICK SEE DETAILS", [
                    'qrcode' => $content,
                    'current_modelname' => $currentModel,
                    'manual_code_content_should_be' => $masterContent->toArray()
                ]);
            }
        } else {
            # code...
            $currentModel = (\method_exists($this, 'getModelname')) ? $this->getModelname() : 'unknown';
            $masterContent = MasterManualInstruction::select(['content', 'modelname'])
                ->where('modelname', $currentModel)
                ->get();

            throw new StoreResourceFailedException("TOLONG PASTIKAN MANUAL INSTRUCTION SESUAI MODELNYA. CLICK SEE DETAILS", [
                'qrcode' => $content,
                'current_modelname' => $currentModel,
                'manual_code_content_should_be' => $masterContent->toArray()
            ]);
        }
        
    }
}