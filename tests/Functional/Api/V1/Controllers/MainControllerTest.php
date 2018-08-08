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

    protected $endpoint = 'api/main';

    protected $parameter = [
        'board_id'  => '00001IA01001005',
        'nik'       =>  '39597',
        'ip'        => '::1', //localhost
    ];

    public function testScanSuccess(){
        $this->post($this->endpoint, $this->parameter )
        ->assertJsonStructure([
            'success',
            'message'
        ])->assertJson([
            'success' => true,
            'message' => "data saved!"
        ])

    }

}
