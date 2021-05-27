<?php
namespace TJM\PHPTimesheet;
use Exception;

class Sheet{
	protected $periods = [];
	public function __construct($in){
		if(is_array($in)){
			$this->periods = $in;
		}elseif(is_string($in)){
			foreach(explode('-----', $in) as $period){
				$this->periods[] = new PayPeriod("-----" . $period);
			}
		}
	}
	// public function __toString(){
	// }
	public function getHours($format = 'roundDecimalHours'){
		$hours = 0;
		foreach($this->periods as $period){
			$hours += $period->getHours();
		}
		return $hours;
	}
	public function getPeriods(){
		return $this->periods;
	}
	public function getTimes(){
		$times = [];
		foreach($this->periods as $period){
			$times = array_merge($times, $period->getTimes());
		}
		return $times;
	}
}
