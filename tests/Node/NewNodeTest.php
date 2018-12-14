<?php

namespace App\Functional\Api\V1\Controllers;

use App\NewTestCase as TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Dingo\Api\Exception\StoreResourceFailedException;
use App\Api\V1\Helper\Node;
use App\Board;
class NewNodeTest extends TestCase
{
    protected $mock;

    protected function initMock(){
        $this->mock = new Node(null, true );
        return $this->mock;
    }

    public function getMock(){
        $mock = (is_null($this->mock)) ? $this->initMock() : $this->mock;
        return $mock;
    }

    protected function seedBoards(array $parameters){
        factory(Board::class)->create($parameters);
    }

    public function testVerifyModelnameAndLotnoTicketMaster(){
        $node = $this->getMock();
        $guidTicket = 'dummy-guid-ticket';
        $guidMaster = 'dummy-guid-master';
        
        $this->seedBoards([
            'guid_ticket'=>$guidTicket,
            'modelname' => 'DDXGT700RA9N',
            'lotno' => '011A',
        ]);

        $this->seedBoards([
            'guid_ticket'=>$guidTicket,
            'modelname' => 'DDXGT700RA9N',
            'lotno' => '011A',
        ]);

        $this->seedBoards($guidTicket, $guidMaster);
        
        $result = $node->VerifyModelnameAndLotnoTicketMaster($guidTicket, $guidMaster);
        $this->assertNull($result);
    }
}
