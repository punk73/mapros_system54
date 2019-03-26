<?php

namespace App\Api\V1\Traits;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Http\Request;
use App\ManualInstruction;

trait ManualInstructionTrait {
    public function storeManualContent($data) {
        $guidMaster = $this->getGuidMaster();
        $content  = $data;

        $manualInstruction = new ManualInstruction();
        $manualInstruction->guid_master = $guidMaster;
        $manualInstruction->content = $content;
        
        $manualInstruction->save();
    }
}