<?php

namespace App\Functional\Api\V1\Controllers;

use App\NewTestCase as TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Dingo\Api\Exception\StoreResourceFailedException;
use Illuminate\Support\Facades\Artisan;
use DB;

class CriticalPartTraitTest extends TestCase
{
    protected $mock;
    
    protected function getDummyData(){
        $data = 'B46-0825-00     2629991 200   I10775 B46-0825-00    201809021630379758000001          14124313451435435435465654645';
        return $data;
    }
    protected function initMock(){
        $this->mock = $this->getMockForTrait('App\Api\V1\Traits\CriticalPartTrait');
        return $this->mock;
    }

    public function getMock(){
        $mock = (is_null($this->mock)) ? $this->initMock() : $this->mock;
        return $mock;
    }

    public function testSave(){}
    
    public function testGetSetCriticalPart(){
        $mock = $this->getMock();
        $this->assertNull( $mock->getCriticalPart() );
        $data = $this->getDummyData();
        $mock->setCriticalPart($data);
        $this->assertEquals($data,  $mock->getCriticalPart() );
        $this->assertNotNull( $mock->getExtractedCriticalParts() );
        $this->assertTrue( $mock->isCriticalPartExtracted( $mock->getExtractedCriticalParts() ) );
    }

    public function testSetCriticalPartErrorWhenSettingNonCriticalPart(){
        $mock = $this->getMock();
        $nonCriticalPart = ['B46-0825-00     2629991 12    I10775 B46-0825-00    201809021630107354000002          '];
        $this->expectException(StoreResourceFailedException::class); //exeption expected cuz the parameter not critical parts;
        $mock->setCriticalPart($nonCriticalPart);
    }

    public function testIsCriticalPartReturnTrue(){
        $mock = $this->getMock();
        $data = $this->getDummyData();
        $this->assertTrue( $mock->isCriticalPart($data) );
    }

    public function testIsCriticalPartReturnFalse(){
        $mock = $this->getMock();
        $data = 'B46-0825-00     2629991 12    I10775 B46-0825-00    201809021630107354000002          ';
        $this->assertFalse( $mock->isCriticalPart($data) );
        $data = ['B46-0825-00     2629991 12    I10775 B46-0825-00    201809021630107354000002          '];
        $this->assertFalse( $mock->isCriticalPart($data) );

    }

    public function testExtractCriticalPart(){
        $mock = $this->getMock();
        $data = $this->getDummyData();
        $contains = [
            'part_no',
            'po',
            'qty',
            'unique_id',
            'supp_code',
            'production_date',
            'lotno',
        ];

        $extracted = $mock->extractCriticalPart($data);

        foreach ($contains as $key => $value) {
            $this->assertArrayHasKey($value, $extracted );
        }
    }

    /*public function testIsCriticalExists(){
        $data = $this->getDummyData();
        $mock = $this->getMock();
        $data = $mock->extractCriticalPart($data);
        $isExists = $mock->isCriticalExists($data);
        $this->assertFalse($isExists);
    }*/

    /*public function testIsCriticalExistsWithStringParameter(){
        $data = $this->getDummyData();
        $mock = $this->getMock();
        $isExists = $mock->isCriticalExists($data);
        $this->assertFalse($isExists);
    }*/

    public function testInsertIntoCritical(){
        $mock = $this->getMock();
        $data = [$this->getDummyData()];
        $unique_id = 'boards-id';
        /*seed the database*/
        $this->seedCriticalDb(3);

        $criticalScannerData = [
            "line_id" =>  2,
            "lineprocess_id" =>  55,
            "scan_nik" =>  "39597"
        ];

        $mock->insertIntoCritical($data, $unique_id, $criticalScannerData );

        $this->assertDatabaseHas('criticals', $mock->extractCriticalPart($this->getDummyData()) );
        $this->assertDatabaseHas('critical_node', [
            'unique_id' => $unique_id
        ]);
    }

    public function testIsCriticalPartExtracted(){
        $mock = $this->getMock();
        $data = $this->getDummyData();
        $this->assertFalse( $mock->isCriticalPartExtracted($data));
    }

    public function testIsCriticalPartExtractedWithParameterArray(){
        $mock = $this->getMock();
        $data = $this->getDummyData();
        $this->assertFalse( $mock->isCriticalPartExtracted([$data]) ); 
    }

    public function testIsCriticalPartExtractedWithParameterArrayReturnTrue(){
        $mock = $this->getMock();
        $data = $mock->extractCriticalPart( $this->getDummyData() );
        $this->assertTrue( $mock->isCriticalPartExtracted($data) );
    }

    public function testIsCriticalPartExtractedReturnTrue(){
        $mock = $this->getMock();
        $data = $mock->extractCriticalPart( $this->getDummyData() );
        $this->assertTrue( $mock->isCriticalPartExtracted([$data]) );
    }

    public function testSaveToPivot(){
        $mock = $this->getMock();
        $criticalId = 1;
        $unique_id = '9EB02277-8732-4C43-A9A2-D3E38A70105E';

        $this->seedCriticalDb(2);
        /*make sure criticals dengan id 1 is exists*/
        $this->assertDatabaseHas('criticals', ['id'=>1]);

        $mock->saveToPivot($criticalId, $unique_id);

        // assert database with specific record exists
        $this->assertDatabaseHas('critical_node', [
            'critical_id'=> $criticalId,
            'unique_id'=> $unique_id,
        ]);
    }

    public function testSaveToPivotError(){
        $mock = $this->getMock();
        $this->seedCriticalDb(1);
        
        $criticalId = 1;
        $unique_id = '9EB02277-8732-4C43-A9A2-D3E38A70105E';
        $this->expectException(StoreResourceFailedException::class); //exeption expected cuz the parameter not critical parts;
        $mock->saveToPivot($criticalId, $unique_id);

    }

    private function seedCriticalDb($qty = 1 ){
        /*insert into db critical*/
        DB::table('criticals')->insert([
            #id, line_id, lineprocess_id, unique_id, supp_code, part_no, po, production_date, lotno, qty, scan_nik, created_at, updated_at
            // 'id'    => 1,
            'line_id' => '1',
            'lineprocess_id' => '12',
            'unique_id' => 'I10775 B46-0825-00    201809021634487676000001',
            'supp_code' => '10775',
            'part_no' => 'B46-0825-00    ',
            'po' => '2629991',
            'production_date' => '20180902',
            'lotno' => '1341243115345456',
            'qty' => $qty,
            'scan_nik' => '0037299U',
            'created_at' => '2018-09-02 16:35:59',
            'updated_at' => '2018-09-02 16:35:59'
        ]);
        /*insert into db critical_node*/
        DB::table('critical_node')->insert([
            'critical_id' =>1,
            'unique_id' => 'some-dummy-unique-id'
        ]);
    }

    public function testIsRunOutReturnFalse(){
        $mock = $this->getMock();
        $this->seedCriticalDb(200);
        // run isRunOut method with critical_id = 1
        $isRunOut = $mock->isRunOut(1);
        $this->assertFalse($isRunOut);
    }

    public function testIsRunOutReturnTrue(){
        $mock = $this->getMock();
        $this->seedCriticalDb(1);
        $this->assertDatabaseHas('criticals', ['qty' => 1] );
        // run isRunOut method with critical_id = 1
        $isRunOut = $mock->isRunOut(1, 'IN');
        $this->assertTrue($isRunOut);
    }

    // public function test


}
