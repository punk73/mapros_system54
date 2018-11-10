<?php
namespace App\Api\V1\Interfaces;

interface CriticalPartInterface {
	public function setCriticalPart($criticalPart);
	public function getCriticalPart();
	public function isCriticalPart($criticalPart);
	// public function isCriticalExists($result);
	public function insertIntoCritical($extractedCriticalParts, $uniqueId, $criticalScannerData );
	public function extractCriticalPart($criticalPart);
}