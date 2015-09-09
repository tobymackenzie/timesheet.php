<?php
namespace TJM\PHPTimesheet;

class TimeSheetMigrator{
	static public function getTimes($data){
		$times = Array();
		foreach($data['work'] as $item){
			if(isset($item['times'])){
				$times = array_merge($times, $item['times']);
			}
		}
		return $times;
	}
	static public function parse($string){
		$results = Array(
			'work'=> Array()
		);
		$lines = explode("\n", $string);
		foreach($lines as $line){
			if($line){
				$lineBits = explode(':', $line);
				$item = Array(
					'date'=> trim($lineBits[0])
					,'times'=> Array()
				);
				$times = explode(',', trim($lineBits[1]));
				foreach($times as $time){
					$item['times'][] = new TimePeriod($time);
				}
				$results['work'][] = $item;
			}
		}
		return $results;
	}
}
