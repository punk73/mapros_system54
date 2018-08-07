<?php

namespace App\Functional\Api\V1\Controllers;

use App\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Api\V1\Helper\Node;
use App\Board;
use App\Scanner;
use App\Lineprocess;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Dingo\Api\Exception\StoreResourceFailedException;

class NodeTest extends TestCase
{
    use DatabaseMigrations;

    protected $parameter = [
        'board_id'=> '00001IA01001007', //KW-R710H3A9N
        'nik' => '39596',
        'ip' => '::1', //localhost scanner
        'is_solder' => false,
    ];

    /*
    * @instantiate Node class
    * 
    *
    *
    */

    public function testInstanstiateNodeClassSuccess(){
        $this->seedDb(); //seed the table

        $node = new Node($this->parameter);

        $this->assertInstanceOf('App\Board', $node->getModel() );
        $this->assertInstanceOf('App\Scanner', $node->getScanner() );
        $this->assertInstanceOf('App\Lineprocess', $node->getLineprocess() );
        $this->assertNotNull($node->scanner_id);
        $this->assertNotNull($node->dummy_id);
        $this->assertNotNull($node->nik);

        // $this->assertNotNull($node->process);
    }

    public function testGetSequence(){

    }



    private function addModel(){
        $board = new Board;
        $board->scanner_id = 11; //scanner_id untuk ip ::1
        $board->board_id = $this->parameter['board_id'];
        $board->scan_nik = $this->parameter['nik'];
        $board->status = 'in';
        $board->save();
    }

    public function seedDb(){
        // it's mean to seed the db for testing purpose;
        // Artisan::call('migrate:refresh');
        // Artisan::call('db:seed', ['--class'=>'ScannerSeeder'] );
        Artisan::call('db:seed');
    }

    public function testIsExistsReturnFalse(){
        $board = Board::all();
        $this->assertEquals(count($board), 0, 'this model should empty for testing it' );

        $this->seedDb();

        $node = new Node($this->parameter);
        $this->assertEquals( false, $node->isExists() );
    }

    public function testIsExistsReturnTrue(){
        $this->addModel();
        // seed the scanner database
        $this->seedDb();

        $board = Board::all();
        $scanners = Scanner::where('ip_address', '::1')->get();

        // assertGreaterThan( $idealValue , $assertedValue )
        $this->assertGreaterThan( 0, count($scanners));
        $this->assertGreaterThan( 0, count($board));

        $node = new Node($this->parameter);

        $this->assertEquals(11, $node->scanner_id );
        // assertEquals($expected, $actual )
        $this->assertEquals( true, $node->isExists() );
    }

    public function testGetBoardTypeSuccess(){
        $this->seedDb();

        $parameter = $this->parameter;
        $node = new Node($parameter);
        
        $boardType = $node->getBoardType();
        $boardType = (array) $boardType;

        $this->assertArrayHasKey('name', $boardType['board']);
        $this->assertArrayHasKey('pwbname', $boardType['board']);
        $this->assertNotNull($boardType['board']['name']);
        $this->assertNotNull($boardType['board']['pwbname']);
    }

    public function testGetBoardTypeFailedDataNotFound(){
        $parameter = $this->parameter;
        $parameter['board_id'] = 'FFFFFIA01001007'; // --> it's set to be not found!
        $node = new Node($parameter);
        
        $boardType = $node->getBoardType();
        $boardType = (array) $boardType;
        
        $this->assertArrayHasKey('name', $boardType['board']);
        $this->assertArrayHasKey('pwbname', $boardType['board']);
        $this->assertNull($boardType['board']['name']);
        $this->assertNull($boardType['board']['pwbname']);   
    } 

    public function testGetSequenceSuccess(){
    }

    public function testPrevMethod(){
    }

    private function addLineprocess(){
        $lineprocess = new Lineprocess([
            'name' => 'test process',
            'type' => 1, //internal
            'std_time' => 30, //30 seconds
        ]);
    }

    public function setScannerFailed(){
        // set expected exception
        // below exception throw when scanner ip not found;
        $this->expectException(StoreResourceFailedException::class);
        
        $node = new Node($this->parameter);
    }

    public function testSetLineprocessSuccess(){
        // add lineprocess into
        $this->seedDb();
        // $node->setLineprocess($id) is triggered in constructor;
        $node = new Node($this->parameter);

        $this->assertInstanceOf('App\Lineprocess', $node->lineprocess);
    }

    public function testSetLineprocessFailedNotFound(){
        // set expected exception
        $this->expectException(StoreResourceFailedException::class);
        // run method that triggered the exception due to model not found;
        
        // $node->setLineprocess($lineprocessId) is triggered in constructor;
        $node = new Node($this->parameter);
    }

}
