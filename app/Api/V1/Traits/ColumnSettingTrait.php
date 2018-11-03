<?php
namespace App\Api\V1\Traits;

use App\ColumnSetting;
use Dingo\Api\Exception\StoreResourceFailedException;

trait ColumnSettingTrait {

	protected $column_setting;

	public function getColumnSetting(){
		return $this->column_setting;
	}

	public function setColumnSetting( $columnSetting = null ){
		$this->column_setting = $columnSetting;
	}

	public function isJoin(){
		if ($this->column_setting == null ) {
			$this->column_setting = [];
		}

		return (count($this->column_setting) > 1 );
	}

	public function getColumnSettingWhereCodePrefix($code){
		return ColumnSetting::where('code_prefix', $code )->first();
	}

	/*
	* bool isSettingContainChildrenOf();
	* level 1 adalah yg paling tinggi. 
	* level 2, adalah anaknya level 1;
	*/
	public function isSettingContainChildrenOf($parent = 'master'){
		$tableName = $parent.'s';
		$level = ColumnSetting::select(['level'])->where('table_name', $tableName )->first();
		
		if (!$level) {
			throw new StoreResourceFailedException("column setting dengan table_name = {$tableName} tidak ditemukan.", [
			]);
		}

		$level = $level['level'];
		// return $level;
		$result = false;
		foreach ($this->column_setting as $key => $setting) {
			$settingLevel = $setting['level'];
			if($settingLevel > $level){
				return $result = true;
			}
		}
		return $result;
	}

}