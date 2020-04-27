<?php

namespace App\Functional\Api\V1\Controllers;

use App\NewTestCase as TestCase;
use App\Api\V1\Helper\Node;

class ReworkTest extends TestCase {
    public function testStoreSerialNumberRework(){
        // $this->assertTrue(true);

        $node = new Node(null, true);
        $guid = "95A25DE7-EF5C-4D6F-95D7-9B7A2ABCDE30";
        $node->storeSerialNumberRework($guid);

        $this->assertDatabaseHas('rework', ['barcode' => "DDXGT700RA9N some serial no"]);
    }
}