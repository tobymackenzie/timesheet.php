<?php
namespace TJM\PHPTimesheet;
use Exception;

class PayPeriod{
	protected $days = [];
	protected $notes = [];
	public function __construct($days){
		if(is_string($days)){
			$days = explode("\n", $days);
		}
		if(count($days)){
			if(is_string($days[0]) && substr($days[0], 0, 5) === '-----'){
				array_shift($days);
			}
			foreach($days as $day){
				if(trim($day)){
					if(preg_match(Day::REGULAR_DAY_REGEX, $day, $matches)){
						$this->days[] = new Day($matches[2], $matches[1] ?? null, $matches[4] ?? null);
					}else{
						$this->notes[] = $day;
					}
				}
			}
		}
	}
	public function __toString(){
		$results = [];
		foreach($this->days as $day){
			$results[] = (string) $day;
		}
		return "-----" . implode("\n", $days) . "\n";
	}
	public function getDays(){
		return $this->days;
	}
	public function getHours($format = 'roundDecimalHours'){
		return TimeClockCalculator::calculateDiffs($this->getTimes(), $format);
	}
	public function getTimes(){
		$times = [];
		foreach($this->days as $day){
			$times = array_merge($times, $day->getTimes());
		}
		return $times;
	}
}
