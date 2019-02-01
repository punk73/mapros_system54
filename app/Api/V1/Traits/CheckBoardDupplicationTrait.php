<?php
namespace App\Api\V1\Traits;

use Dingo\Api\Exception\StoreResourceFailedException;
use App\Board;
use App\Mastermodel;

trait CheckBoardDupplicationTrait {

	public function checkBoardDupplication(){
        if(setting('admin.check_dupplication_board') && ($this->getModelType() == 'board') ){

            if($this->isBoardExists()){
                throw StoreResourceFailedException('board type ini sudah di scan sebelumnya. lanjut board berikutnya.', [

                ]);
            }
        }
    }

    public function isBoardExists($guidParam = null , $scannerIdParam = null, $pwbnameParam = null ){
        $guid = (is_null($guidParam)) ? $this->getUniqueId() : $guidParam;
        $scannerId = (is_null($scannerIdParam)) ? $this->getScanner()['id'] : $scannerIdParam;
        $pwbname = (is_null($pwbnameParam)) ? $this->getBoard()['pwbname'] : $pwbnameParam;

        
        
    }
}