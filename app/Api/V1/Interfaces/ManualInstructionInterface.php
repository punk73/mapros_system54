<?php
namespace App\Api\V1\Interfaces;

interface ManualInstructionInterface {
    public function storeManualContent($data, $guid = null);
    public function getGuidMaster();
    public function getParameter();
    public function hasInstructionManual();
    public function checkInstructionManual();
}