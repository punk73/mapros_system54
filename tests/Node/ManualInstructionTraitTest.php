<?php

namespace App\Functional\Api\V1\Controllers;

use App\NewTestCase as TestCase;

class ManualInstructionTraitTest extends TestCase {

    /* public function index(){
        $this->mock = $this->getMockForTrait('App\Api\V1\Traits\LocationTrait');
        return $this->mock;
    } */

    public function getMocks(){
        return $this->getMockForTrait('App\Api\V1\Traits\ManualInstructionTrait');
    }

    public function testStoreManualContent() {
        $mock = $this->getMocks();
        $content = 'some intruction content sd';
        $guid = 'someguidmaster sd';
        $result = $mock->storeManualContent( $content, $guid );

        $this->assertTrue($result);
        // check data nya masuk
        $this->assertDatabaseHas('manual_instructions', [
            'content' => $content,
            'guid_master' => $guid
        ]);

    }

    public function testStoreManualContentWithNull() {
        $mock = $this->getMocks();
        $content = null;
        
        $result = $mock->storeManualContent($content, 'someguid');
        $this->assertNull($result);
    }

    public function testHasManualIntruction() {
        $mock = $this->getMocks();

        $mock->parameter = ['manual_content' => 'some content'];

        $result = $mock->hasInstructionManual();

        $this->assertTrue($result);

    }

    public function testHasManualInstructionReturnFalse() {
        $mock = $this->getMocks();

        $result = $mock->hasInstructionManual();
        $this->assertFalse($result, 'without specify parameter');
        
        $mock->parameter = ['manual_content' => null ];
        $result = $mock->hasInstructionManual();
        $this->assertFalse($result , 'specify parameter manual content with null');

    }
}