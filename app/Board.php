<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;
use App\BaseModel as Model;

class Board extends Model
{
    protected $table = 'boards';

    protected $fillable = [
    	'board_id',
    	'guid_master',
    	'guid_ticket',
    	'scanner_id',
    	'status',
    	'judge',
    	'scan_nik',
    ];

    public function getActivityDescriptionForEvent($eventName)
    {       

        if ($eventName == 'created')
        {   
            return $this->table .' "'. $this->getData() . '" was created';
        }

        if ($eventName == 'updated')
        {
            return $this->table .' "'. $this->getData() . '" was updated';
        }

        if ($eventName == 'deleted')
        {
            return $this->table .' "'. $this->getData() . '" was deleted';
        }

        return '';
    }

    private function getData(){
        return json_encode([
            'board_id' => $this->board_id,
            'guid_master' => $this->guid_master,
            'guid_ticket' => $this->guid_ticket,
            'scanner_id' => $this->scanner_id,
            'status' => $this->status,
            'judge' => $this->judge,
            'scan_nik' => $this->scan_nik,
        ]);
    }
}
