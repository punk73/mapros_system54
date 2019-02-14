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
        $node = $this->initMock();
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
        $node = $this->initMock();
        $guidTicket = 'dummy-guid-ticket';
        $guidMaster = 'dummy-guid-master';
        $modelname = 'DDXGT700RA9N';

        $this->seedBoards([
            'guid_ticket'=>$guidTicket,
            'modelname' => $modelname,
            'lotno' => '011A',
        ]);

        $this->seedBoards([
            'guid_master'=>$guidMaster,
            'modelname' => $modelname,
            'lotno' => '010A',
        ]);
        
        $board1 = Board::where('guid_ticket', $guidTicket )
        ->where('modelname',$modelname )->exists();
        $this->assertTrue($board1);

        $board2 = Board::where('guid_master', $guidMaster )
        ->where('modelname', $modelname )->exists();
        $this->assertTrue($board2);

        // throw exception ketika lotno berbeda
        $this->expectException(StoreResourceFailedException::class);
        $result = $node->VerifyModelnameAndLotnoTicketMaster($guidTicket, $guidMaster, true );
        
    }

    public function testVerifyModelnameAndLotnoTicketMasterThrowExceptionBedaModel(){
        $node = $this->initMock();
        $guidTicket = 'dummy-guid-ticket';
        $guidMaster = 'dummy-guid-master';
        
        $this->seedBoards([
            'guid_ticket'=>$guidTicket,
            'modelname' => 'DDXGT500RA9N',
            'lotno' => '011A',
        ]);

        $this->seedBoards([
            'guid_master'=>$guidMaster,
            'modelname' => 'DDXGT700RA9N',
            'lotno' => '011A',
        ]);
        
        // throw exception ketika modelname berbeda
        $this->expectException(StoreResourceFailedException::class);
        $result = $node->VerifyModelnameAndLotnoTicketMaster($guidTicket, $guidMaster, true );
    }

    public function testVerifyModelnameAndLotnoTicketMasterThrowExceptionWithoutBoard(){
        $node = $this->initMock();
        $guidTicket = 'dummy-guid-ticket';
        $guidMaster = 'dummy-guid-master';
        
        $this->seedBoards([
            'guid_ticket'=>$guidTicket,
            'modelname' => 'DDXGT500RA9N',
            'lotno' => '011A',
        ]);
   
        // throw exception ketika board tidak ketemu
        $result = $node->VerifyModelnameAndLotnoTicketMaster($guidTicket, $guidMaster, true );
        $this->assertNull($result);
    }
}
