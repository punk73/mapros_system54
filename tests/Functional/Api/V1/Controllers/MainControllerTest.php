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

class MainControllerTest extends TestCase
{
    use DatabaseMigrations;

    protected $parameter = [
        'board_id'=> '00001IA01001007', //KW-R710H3A9N
        'nik' => '39596',
        'ip' => '::1', //localhost scanner
        'is_solder' => false,
    ];

    protected $endpoint = 'api/main/';

    private function seedDb(){
        Artisan::call('db:seed');
    }

    private function addBoard(){
        $board = new Board([
            'board_id' => $this->parameter['board_id'],
            'scanner_id' => 1,
            'status' => 'IN',
            'judge' => 'OK',
            'scan_nik'=> '39597',
        ]);

        $board->save();
    }

    public function testScanSuccess(){
        $this->seedDb();
        $this->addBoard();

        $board = Board::all();
        fwrite(STDOUT, var_dump($board[0]));

        $scanners = Scanner::all();
        fwrite(STDOUT, var_dump($scanner[0]));


        $this->post($this->endpoint, $this->parameter )
        ->assertJsonStructure([
            'success',
            'message'
        ])->assertJson([
            'success' => true,
            'message' => "data saved!"
        ]);

    }

}
