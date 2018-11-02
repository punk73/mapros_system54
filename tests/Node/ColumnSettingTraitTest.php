<?php

namespace App\Functional\Api\V1\Controllers;

use App\NewTestCase as TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Dingo\Api\Exception\StoreResourceFailedException;
use App\ColumnSetting;

class ColumnSettingTraitTest extends TestCase
{
    protected $mock;

    protected function initMock(){
        $this->mock = $this->getMockForTrait('App\Api\V1\Traits\ColumnSettingTrait');
        return $this->mock;
    }

    protected function seedColumnSetting(){
        $columnSettings = factory(ColumnSetting::class, 1 )->create();
        return $columnSettings;
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
    public function testGetAndSetColumnSetting(){
        $mock = $this->getMock();
        $this->assertNull( $mock->getColumnSetting() );
        $columnSetting = 'someData';
        $mock->setColumnSetting($columnSetting);
        $this->assertEquals( $columnSetting, $mock->getColumnSetting() );
    }

    public function testIsJoinReturnFalse(){
       $mock = $this->getMock();
       $this->assertFalse( $mock->isJoin() );
    }

    public function testIsJoinReturnTrue(){
        $mock = $this->getMock();
        $dummy = $this->getDummyData();
        $mock->setColumnSetting($dummy);
        $this->assertTrue($mock->isJoin());
    }

    public function testGetColumnSettingWhereCodePrefix(){
        $mock = $this->getMock();
        /*factory will seed our database with preset data;*/
        $this->seedColumnSetting();
        /*this test will be pass if we had records MAMST on column_settings table */
        $data = $mock->GetColumnSettingWhereCodePrefix('MAMST');
        $this->assertNotNull($data);
        $this->assertInstanceOf('App\ColumnSetting', $data );
    }

    public function testIsSettingContainChildrenOf(){
        /*factory will seed our database with preset data;*/
        $this->seedColumnSetting();

        $mock = $this->getMock();
        $this->assertNull($mock->getColumnSetting());
        $mock->setColumnSetting($this->getDummyData());
        $this->assertNotNull($mock->getColumnSetting());
        $this->assertTrue($mock->isSettingContainChildrenOf('master'));
    }
}
