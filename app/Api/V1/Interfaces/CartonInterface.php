<?php
namespace App\Api\V1\Interfaces;

interface CartonInterface {
    public function storeCarton($data, $guid = null);
    public function getGuidMaster();
    public function getParameter();
    public function getModelname();
    public function hasCarton();
    public function checkCarton();
}