<?php
namespace App\Api\V1\Interfaces;

interface LocationInterface {
	public function insertLocation(Array $locations);
	public function verifyLocations($locations);
	public function decodeLocations($locations);
}