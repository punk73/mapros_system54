<?php

namespace App\Api\V1\Traits;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Http\Request;
use App\ManualInstruction;

trait ManualInstructionTrait {
    public function storeManualContent(Request $request) {
        $guidMaster = $this->getGuidMaster();
        $content  = $request->only('model_content');

        $manualInstruction = new ManualInstruction();
        $manualInstruction->guid_master = $guidMaster;
        $manualInstruction->content = $content;
        
        $manualInstruction->save();
    }
}