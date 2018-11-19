<?php
namespace App\Api\V1\Interfaces;
use Illuminate\Database\Eloquent\Model;

interface RepairableInterface {
	/*required to implemented in node class */
	public function getModel();
	public function getUniqueColumn();
	public function getUniqueId();
	public function getProcess();
	/*end*/
	public function getJoinQuery(Model $modelParam = null);
	public function getStartId($idParam = null );
	public function isRepaired($uniqueIdParam = null, $lineprocessNgExists = null );
	public function getLineprocessNg(Model $model, $uniqueColumn, $uniqueId);
	public function isAfterNgProcess($processParam = null, $lineprocessId =null, $lineprocessNg = null);


}