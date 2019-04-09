<?php

namespace App\Functional\Api\V1\Controllers;

use App\NewTestCase as TestCase;
use App\LineprocessCarton;

class CartonTraitTest extends TestCase {


    public function getMocks(){
        return $this->getMockForTrait('App\Api\V1\Traits\CartonTrait');
    }

    public function testStoreCarton() {
        $mock = $this->getMocks();
        $content = 'some intruction content sd';
        $guid = 'someguidmaster sd';
        $result = $mock->storeCarton( $content, $guid );

        $this->assertTrue($result);
        // check data nya masuk
        $this->assertDatabaseHas('cartons', [
            'content' => $content,
            'guid_master' => $guid
        ]);

    }

    public function testStoreCartonWithNull() {
        $mock = $this->getMocks();
        $content = null;
        
        $result = $mock->storeCarton($content, 'someguid');
        $this->assertNull($result);
    }

    public function testHasCarton() {
        $mock = $this->getMocks();

        $mock->parameter = ['manual_content' => 'some content'];

        $result = $mock->hasCarton();

        $this->assertTrue($result);

    }

    public function testHasCartonReturnFalse() {
        $mock = $this->getMocks();

        $result = $mock->hasCarton();
        $this->assertFalse($result, 'without specify parameter');
        
        $mock->parameter = ['manual_content' => null ];
        $result = $mock->hasCarton();
        $this->assertFalse($result , 'specify parameter manual content with null');

    }

    protected function seedLineprocessIntruction() {
        LineprocessCarton::insert([
            'scanner_id'=> 1,
            'lineprocess_id' => 1,
            'modelname' => 'DDXGT700RA9N',
            'has_check' => 1
        ]);
    }

    public function testcheckCarton() {
        $mock = $this->getMocks();

        $this->seedLineprocessIntruction();
        $result = $mock->checkCarton(1,1, 'DDXGT700RA9N');

        $this->assertTrue($result);
    }

    public function testCheckInstructionManualReturnFalse() {
        $mock = $this->getMocks();

        $result = $mock->checkCarton(1,1,'DDXGT700RA9N');

        $this->assertFalse($result);
    }
}