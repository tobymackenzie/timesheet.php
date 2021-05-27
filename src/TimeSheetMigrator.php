<?php
namespace TJM\PHPTimesheet;

class TimeSheetMigrator{
	static public function getTimes($data){
		return $data->getTimes();
	}
	static public function parse($string){
		return new Sheet($string);
	}
}
