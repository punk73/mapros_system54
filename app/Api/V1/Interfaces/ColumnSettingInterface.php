<?php
namespace App\Api\V1\Interfaces;

interface ColumnSettingInterface {

	public function initColumnSetting();

	/*return column_setting*/
	public function getColumnSetting();

	/*set column_setting*/
	public function setColumnSetting();

	/*determine whether or this node is join*/
	public function isJoin();
	/*get column setting with specifix code_prefix from database*/
	public function getColumnSettingWhereCodePrefix($code);
	/*return boolean wheter or not specific model_type has chilren*/
	public function isSettingContainChildrenOf($parent = 'master');
}