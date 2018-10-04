<?php

use Illuminate\Database\Seeder;
use App\Symptom;

class SymptomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {	
    	$datas= $this->getData();
        foreach ($datas as $key => $data) {
        	$line = new Symptom;
            $line->code = $key + 1 ; // index array mulai dari 0;
            $line->category = $data;
        	$line->save();
        }
    }

    public function getData(){
    	return [
    		"SCRATCH",
            "DENTED",
            "BUTTON JAMMED",
            "BUTTON RUBBING",
            "FLOATING",
            "BLUR",
            "SHIFTING",
            "NOISE",
            "WHITE DOT",
            "BLACK DOT",
            "FLOW MARK",
            "NO FUNCTION",
            "MALFUNCTION",
            "CAN'T LOADING",
            "CAN'T EJECT",
            "HANG UP",
            "OILY",
            "CLOUDY",
            "FINGER PRINT",
            "DIRTY",
            "DUST",
            "HAIR MARK",
            "DISPLAY FLICKER",
            "WHITE LINE",
            "NO POWER",
            "WRONG PART NUMBER",
            "ASSY REVERSE POSITION",
            "HARD CLICK",
            "PANEL DIFFICULT TO ATTACH",
            "BENDING",
            "LIGHT LEAKAGE",
            "EXTRA SCREW",
            "MISSING SCREW",
            "MISSING TAPPING",
            "PEEL OFF",
            "MISSING SEGMENT",
            "RUSTY",
            "SLANTING",
            "FOLDING",
            "SINK MARK",
            "STAIN",
            "CRACK",
    	];
    }
}
