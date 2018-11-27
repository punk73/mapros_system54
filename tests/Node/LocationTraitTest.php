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

    public function getMock(){
        $mock = (is_null($this->mock)) ? $this->initMock() : $this->mock;
        return $mock;
    }

    public function testSetLocations(){
        $mock = $this->getMock();
        $data = $this->getDummyLocation();
        $mock->setLocations($data);

        $this->assertEquals($data, $mock->getLocations() );
    }

    public function testVerifyLocations(){
        $mock = $this->getMock();
        $data = $this->getDummyLocation();
        $result = $mock->verifyLocations($data);

        $this->assertTrue($result);
    }

    public function testVerifyLocationsFalse(){
        $mock = $this->getMock();
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
        $mock = $this->getMock();
        $board = $this->getBoardTransaction();
        $location_id = 1; //dummy

        $pivotId = $mock->saveBoardLocation($board, $location_id);
        $this->assertInternalType('int', $pivotId );

    }


    
}