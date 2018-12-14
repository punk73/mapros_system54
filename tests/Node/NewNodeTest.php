<?php

namespace App\Functional\Api\V1\Controllers;

use App\NewTestCase as TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Dingo\Api\Exception\StoreResourceFailedException;
use App\Api\V1\Helper\Node;
use App\Board;
use App\Master;
use App\Ticket;
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
            'guid_master'=>$guidMaster,
            'modelname' => 'DDXGT700RA9N',
            'lotno' => '011A',
        ]);
        
        $result = $node->VerifyModelnameAndLotnoTicketMaster($guidTicket, $guidMaster, true );
        $this->assertNull($result);
    }

    public function testVerifyModelnameAndLotnoTicketMasterThrowExceptionBedaLot(){
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
            'lotno' => '010A',
        ]);
        
        // throw exception ketika lotno berbeda
        $this->expectException(StoreResourceFailedException::class);
        $result = $node->VerifyModelnameAndLotnoTicketMaster($guidTicket, $guidMaster, true );
    }

    public function testVerifyModelnameAndLotnoTicketMasterThrowExceptionBedaModel(){
        $node = $this->getMock();
        $guidTicket = 'dummy-guid-ticket';
        $guidMaster = 'dummy-guid-master';
        
        $this->seedBoards([
            'guid_ticket'=>$guidTicket,
            'modelname' => 'DDXGT500RA9N',
            'lotno' => '011A',
        ]);

        $this->seedBoards([
            'guid_ticket'=>$guidTicket,
            'modelname' => 'DDXGT700RA9N',
            'lotno' => '011A',
        ]);
        
        // throw exception ketika modelname berbeda
        $this->expectException(StoreResourceFailedException::class);
        $result = $node->VerifyModelnameAndLotnoTicketMaster($guidTicket, $guidMaster, true );
    }

    public function testVerifyModelnameAndLotnoTicketMasterThrowExceptionWithoutBoard(){
        $node = $this->getMock();
        $guidTicket = 'dummy-guid-ticket';
        $guidMaster = 'dummy-guid-master';
        
        $this->seedBoards([
            'guid_ticket'=>$guidTicket,
            'modelname' => 'DDXGT500RA9N',
            'lotno' => '011A',
        ]);
   
        // throw exception ketika board tidak ketemu
        $this->expectException(StoreResourceFailedException::class);
        $result = $node->VerifyModelnameAndLotnoTicketMaster($guidTicket, $guidMaster, true );
    }
}
