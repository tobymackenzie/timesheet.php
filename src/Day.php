<?php
namespace TJM\PHPTimesheet;
use Exception;

class Day{
	const REGULAR_DAY_REGEX = "/^\s*([\d]+)\s*:\s*([0-9\-,]+)\s*(:\s*\(@([^\)]+)\))?/";
	const HOLIDAY_DAY_REGEX = "/^\s*([\d]+)\s*holiday\s+day:?\s*(.*)$/i";
	const SICK_DAY_REGEX = "/^\s*([\d]+)\s*sick\s+day:?\s*(.*)$/i";
	const VACATION_DAY_REGEX = "/^\s*([\d]+)\s*vacation\s+day:?\s*(.*)$/i";
	protected $date;
	protected $description;
	protected $location;
	protected $times = [];
	public function __construct($in, $date = null, $location = null, $description = null){
		if(is_string($in) && preg_match(static::REGULAR_DAY_REGEX, $in, $matches)){
			$date = $matches[1];
			$times = $matches[2] ?? [];
			if(isset($matches[4])){
				$location = $matches[4];
			}
		}elseif(is_string($in) && preg_match(static::HOLIDAY_DAY_REGEX, $in, $matches)){
			if(isset($matches[4])){
				$location = $matches[4];
			}
		}else{
			$times = $in;
		}
		$this->date = $date;
		if(is_string($times)){
			foreach(explode(',', $times) as $time){
				$this->times[] = new TimePeriod($time);
			}
		}
		$this->location = $location;
	}
	public function __toString(){
		$timeStrings = [];
		foreach($this->times as $time){
			$timeStrings[] = (string) $time;
		}
		return "{$this->date}: " . implode($timeStrings) . ": {$this->location}";
	}
	public function getDate(){
		return $this->date;
	}
	public function getHours($format = 'roundDecimalHours'){
		return TimeClockCalculator::calculateDiffs($this->times, $format);
	}
	public function getLocation(){
		return $this->location;
	}
	public function getTimes(){
		return $this->times;
	}
}
