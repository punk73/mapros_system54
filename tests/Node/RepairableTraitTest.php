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

class RepairableTraitTest extends TestCase
{
    protected $mock;

    protected function initMock(){
        $this->mock = $this->getMockForTrait('App\Api\V1\Traits\RepairableTrait');
        return $this->mock;
    }

    protected function getDummyData(){
        return [
            [   
                "id" =>  2,
                "name" =>  "panel",
                "dummy_column" =>  "ticket_no",
                "table_name" =>  "tickets",
                "code_prefix" =>  "MAPNL",
                "level" =>  2,
                "pivot" =>  [
                    "lineprocess_id" =>  55,
                    "column_setting_id" =>  2
                ]                        
            ],
            [
                "id" =>  7,
                "name" =>  "lcd",
                "dummy_column" =>  "barcode",
                "table_name" =>  "parts",
                "code_prefix" =>  "G1P85",
                "level" =>  3,
                "pivot" =>  [
                    "lineprocess_id" =>  55,
                    "column_setting_id" =>  7
                ]
        ]];
    }

    public function getMock(){
        $mock = (is_null($this->mock)) ? $this->initMock() : $this->mock;
        return $mock;
    }

    public function seedTableMaster(){
        $scanners = factory(Scanner::class, 2 )
        ->create([
            'lineprocess_id' => function (){
                return factory(Lineprocess::class)->create()->id;
            }
        ]);
        
        $master = new Master;
        $master->ticket_no_master = 'MAMSTTESTING';
        $master->guid_master = 'some-guid-master-temp';
        $master->status = 'OUT';
        $master->scanner_id = 1; //we'll need to specify this scanner;
        $master->scan_nik = '39597';
        $master->judge = 'NG';
        $master->save();
    }

    public function testGetLineprocessNgThrowException(){
        $mock = $this->getMock();
        $master = new Master;
        $this->expectException(StoreResourceFailedException::class); 
        $mock->getLineprocessNg( $master , 'guid_master', 'someguimaster') ;
    }

    public function testGetLineprocessNgReturnLineprocessId(){
        $mock = $this->getMock();
        
        // jalankan seeder
        $this->seedTableMaster();
        $master = new Master;
        $lineprocessNg = $mock->getLineprocessNg( $master , 'guid_master', 'some-guid-master-temp');
        $this->assertNotNull( $lineprocessNg );
        $this->assertInternalType('int', $lineprocessNg );
    }

    public function testGetJoinQuery(){
        $mock = $this->getMock();
        $master = new Master;
        $data = $mock->getJoinQuery($master);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Builder', $data );    
    }

    public function testIsAfterNgProcess(){
        $mock = $this->getMock();
        /*parameters : process, lineprocess_id, lineprocessNg*/
        $true = $mock->isAfterNgProcess('1,2,3,4,5', 4, 1 );
        $this->assertTrue($true);

        $false = $mock->isAfterNgProcess('1,2,3,4,5', 2, 4 );
        $this->assertFalse($false);
    }

    public function testIsAfterNgProcessError(){
        $mock = $this->getMock();
        /*below exeption will be thrown*/
        $this->expectException(StoreResourceFailedException::class);
        /*parameters : process, lineprocess_id, lineprocessNg*/
        $true = $mock->isAfterNgProcess('1,2,3,4,5', 7, 1 );
        
    }
}