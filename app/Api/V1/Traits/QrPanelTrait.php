<?php

namespace App\Api\V1\Traits;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Http\Request;
use App\LogQrPanel;
use Illuminate\Database\QueryException;

trait QrPanelTrait {
    public function storeQrPanel($data , $guid = null ) {
        $guidMaster = ($guid == null )? $this->getGuidMaster() : $guid;
        $guidTicket = ($guid == null )? $this->getGuidTicket() : $guid;
        $content  = $data;

        if($content == null || $guidTicket == null) {
            return null; // ??
        }

        if(isset($content)){
            $logQrPanel = new LogQrPanel();
            $logQrPanel->guid_master = $guidMaster;
            $logQrPanel->guid_ticket = $guidTicket;
            $logQrPanel->content = $content;
            $result = $logQrPanel->save();

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

    
}