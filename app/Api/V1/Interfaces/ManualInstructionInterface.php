<?php
namespace App\Api\V1\Interfaces;

use Dingo\Api\Http\Request;


interface ManualInstructionInterface {
    public function storeManualContent($data, $guid = null);
    public function getGuidMaster();
    public function hasInstructionManual();
}