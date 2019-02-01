<?php
namespace App\Api\V1\Traits;

use Dingo\Api\Exception\StoreResourceFailedException;
use App\Board;
use App\Mastermodel;

trait CheckBoardDupplicationTrait {

	public function checkBoardDupplication(){
        if(setting('admin.check_dupplication_board') && ($this->getModelType() == 'board') ){

            if(!$this->isBoardExists()){
                throw new StoreResourceFailedException('board type ini sudah di scan sebelumnya. lanjut board berikutnya.', [

                ]);
            }

            return true;
        }
    }

    public function isBoardExists($guidParam = null , $scannerIdParam = null, $pwbnameParam = null ){
        $guid = (is_null($guidParam)) ? $this->getUniqueId() : $guidParam;
        $scannerId = (is_null($scannerIdParam)) ? $this->getScanner()['id'] : $scannerIdParam;
        $pwbname = (is_null($pwbnameParam)) ? $this->getBoard()['pwbname'] : $pwbnameParam;
        $pwbShortName = $pwbname[0] . $pwbname[ count($pwbname) -1 ]; // MN for main, SC for SWRC dst

        $board = Board::select(['id'])
            ->where('guid_master', $guid )
            ->where('scanner_id', $scannerId )
            ->where($this->getUniqueColumn(), 'like', '% {$pwbShortName} %')
            ->first();
        
        return $board;
        
    }
}