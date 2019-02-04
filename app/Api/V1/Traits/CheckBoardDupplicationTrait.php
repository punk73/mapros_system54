<?php
namespace App\Api\V1\Traits;

use Dingo\Api\Exception\StoreResourceFailedException;
use App\Board;
use App\Mastermodel;

trait CheckBoardDupplicationTrait {

	public function checkBoardDupplication($pwbnameParam = null){
        if( setting('admin.check_dupplication_board') && ($this->getModelType() == 'board') && ($this->isJoin()) ){
            $isBoardExists = $this->isBoardExists();
            //jika board denga type seperti itu sudah ada, ga boleh scan lagi.
            if(!$isBoardExists){
                return true;
            }
            
            $pwbname = $this->getBoard()['pwbname'] ;
            throw new StoreResourceFailedException("board type {$pwbname} ini sudah di scan sebelumnya. lanjut board berikutnya.", [
                'board' => $this->getBoard(),
                'node' => $this,
            ]);
        }
    }

    public function isBoardExists($guidParam = null , $scannerIdParam = null, $pwbnameParam = null ){
        $guid = (is_null($guidParam)) ? $this->getUniqueId() : $guidParam;
        $scannerId = (is_null($scannerIdParam)) ? $this->getScanner()['id'] : $scannerIdParam;
        $pwbname = (is_null($pwbnameParam)) ? $this->getBoard()['pwbname'] : $pwbnameParam;
        $pwbShortName = $pwbname[0] . $pwbname[ strlen($pwbname) -1 ]; // MN for main, SC for SWRC dst

        $board = Board::select([
            'id',
            'board_id',
        ])->where('guid_master', $guid )
            ->where('scanner_id', $scannerId )
            ->where($this->getUniqueColumn(), 'like', '%'. $pwbShortName .'%')
            ->first();
        
        return $board;
        
    }
}