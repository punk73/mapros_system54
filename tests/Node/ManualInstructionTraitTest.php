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
        $content = 'some intruction content';
        $guid = 'someguidmaster';
        $mock->storeManualContent( $content, $guid );

        // check data nya masuk
        $this->assertDatabaseHas('manual_instructions', [
            'content' => $content,
            'guid_master' => $guid
        ]);

    }
}