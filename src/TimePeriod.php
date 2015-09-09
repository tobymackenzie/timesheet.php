<?php
namespace TJM\PHPTimesheet;

use Exception;

class TimePeriod{
	/*
	Arguments:
		startOrRange(String): can be start time or range with dash separating start and end time
		end(String): end time if not passing a range as first argument
	*/
	public function __construct($startOrRange, $end = null){
		if($end !== null){
			$start = $startOrRange;
		}else{
			$bits = explode('-', $startOrRange);
			if(count($bits) === 2 && $bits[0] && $bits[1]){
				list($start, $end) = $bits;
			}else{
				throw new Exception('Range string "' . $startOrRange . '" in invalid format.');
			}
		}
		$this->end = $end;
		$this->start = $start;
	}
	public function __toString(){
		return $this->getRange();
	}

	/*
	Property: end
	End of period, in 24 hour time ('Hi').
	*/
	protected $end;
	public function getEnd(){
		return $this->end;
	}

	/*
	Method: getLength
	Get length of time from start to end.
	Argument:
		format(String): See TimeClockCalculator::calculateDiff
	*/
	public function getLength($format = 'object'){
		return TimeClockCalculator::calculateDiff($this->start, $this->end, $format);
	}

	/*
	Method: getRange
	Get start and end as range ("{$start}-{$end}").
	*/
	public function getRange(){
		return "{$this->start}-{$this->end}";
	}

	/*
	Property: start
	Start of period, in 24 hour time ('Hi').
	*/
	protected $start;
	public function getStart(){
		return $this->start;
	}
}
