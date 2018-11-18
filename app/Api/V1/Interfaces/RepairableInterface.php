<?php
namespace App\Api\V1\Interfaces;

interface RepairableInterface {
	public function getLineprocessNg();
	public function isAfterNgProcess();
}