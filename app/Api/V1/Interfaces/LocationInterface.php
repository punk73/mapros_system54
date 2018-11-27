<?php
namespace App\Api\V1\Interfaces;
use App\Board;

interface LocationInterface {
	public function insertLocation(Board $board, Array $locations);
	public function verifyLocations($locations);
	public function decodeLocations($locations);
	public function setLocations($locations);
	public function getLocations();
}