<?php

namespace App\Functional\Api\V1\Controllers;

use App\NewTestCase as TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Dingo\Api\Exception\StoreResourceFailedException;
use App\Master;
use App\Scanner;
use App\Lineprocess;
use App\LineprocessStart;
use App\Repair;
use App\Location;
use App\Board;
use App\Symptom;
use DB;

class LocationTraitTest extends TestCase
{
    protected $mock;

    protected function initMock(){
        $this->mock = $this->getMockForTrait('App\Api\V1\Traits\LocationTrait');
        return $this->mock;
    }

    protected function getDummyLocation(){
        return [
            [
                'ref_number_id'  =>  1,
                'symptoms_id'    => ['1','2','3']
            ],[
                'ref_number_id'  => 2,
                'symptoms_id'    => [ '2','3' ]
            ]
        ];
    }

    public function getMocks(){
        $mock = (is_null($this->mock)) ? $this->initMock() : $this->mock;
        return $mock;
    }

    public function testSetLocations(){
        $mock = $this->getMocks();
        $data = $this->getDummyLocation();
        $mock->setLocations($data);

        $this->assertEquals($data, $mock->getLocations() );
    }

    public function testVerifyLocations(){
        $mock = $this->getMocks();
        $data = $this->getDummyLocation();
        $result = $mock->verifyLocations($data);

        $this->assertTrue($result);
    }

    public function testVerifyLocationsFalse(){
        $mock = $this->getMocks();
        $data = [];
        $testCase1 = $mock->verifyLocations($data);
        $data2 = [[
            'ref_number_id'  => 2,
            'symptoms_id'    => [] //empty array is forbiden
        ]];

        $testCase2 = $mock->verifyLocations($data2);

        $data3 = [
            'ref_number_id'  => 2,
            'symptoms_id'    => [3,2,5]
        ]; // error karena array nya satu dimensi;
        $testCase3 = $mock->verifyLocations($data3);

        $this->assertFalse($testCase1);
        $this->assertFalse($testCase2);
        $this->assertFalse($testCase3);
    }

    protected function getBoardTransaction(){
        $board = new Board;
        $board->board_id = 'dummy';
        $board->modelname = 'dummy-model';
        $board->status = 'OUT';
        $board->judge = 'OK';
        $board->scan_nik = '39597';
        $board->scanner_id = 1;
        $board->save();

        return $board; 
    }

    public function testSaveBoardLocation(){
        $mock = $this->getMocks();
        $board = $this->getBoardTransaction();
        $location_id = factory(\App\Location::class)->create()->id; //dummy

        $pivot = $mock->saveBoardLocation($board, $location_id);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\Pivot', $pivot );
    }

    public function testSaveLocationSymptoms(){
        $board = factory(Board::class)->create();
        $locations = factory(Location::class, 2)->create();
        $board->locations()->attach($locations);
        $locations = $board->locations;

        foreach ($locations as $key => $location) {
            $pivot = $location->pivot;
        }

        $symptom_code = factory(Symptom::class)->create()->code;

        $mock = $this->getMocks();
        $result = $mock->saveLocationSymptoms($pivot, [$symptom_code] );

        $board_location_symptom = DB::table('board_location_symptom')->select('id')->count();
        // cek data di database masuk;
        $this->assertGreaterThan( 0, $board_location_symptom );
        
    }
}