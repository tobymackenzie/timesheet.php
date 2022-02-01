<?php
namespace TJM\PHPTimesheet;

use DateInterval;
use DateTime;

class TimeClockCalculator{
	protected $rate = 7.25;
	static public $verbose = false;

	/*
	Create a "Time" object from a string
	*/
	static public function createTime($string){
		return new DateTime($string);
	}
	/*
	Calculate the difference between two times
	Arguments:
		start(String|DateTime): start time
		end(String|DateTime): end time
		format(String): format to return, one of:
			object: DateInterval object
			decimal: float
	*/
	static public function calculateDiff($start, $end, $format = 'object'){
		if(!is_object($start)){
			$start = static::createTime($start);
		}
		if(!is_object($end)){
			$end = static::createTime($end);
		}
		$diff = $end->diff($start);
		switch($format){
			case 'object':
				return $diff;
			break;
			case 'decimal':
				return static::getDecimalHours($diff);
			break;
		}
	}
	/*
	Perform `$this->calculateDiff()` on an array of time ranges, adding up all diffs
	Arguments:
		times(Array): array of time range strings (see TimePeriod constructor) or TimePeriod objects
		format(String): what format to return.  One of:
			decimalHours: hours in decimal format
			roundedDecimalHours: round the decimal hours value
			object: DateInterval object
	*/
	static public function calculateDiffs($times, $format = 'object'){
		$hours = 0;
		$minutes = 0;
		foreach($times as $time){
			if(!is_object($time)){
				$time = new TimePeriod($time);
			}
			$diff = $time->getLength();
			$hours += $diff->h;
			$minutes += $diff->i;
			if(static::$verbose){
				echo "adding {$diff->h} hours and {$diff->i} minutes\n";
			}
			// $timeBits = explode('-', $time);
			// if(count($timeBits) === 2){
			// 	$diff = static::calculateDiff($timeBits[0], $timeBits[1]);
			// 	$hours += $diff->h;
			// 	$minutes += $diff->i;
			// 	if(static::$verbose){
			// 		echo "adding {$diff->h} hours and {$diff->i} minutes\n";
			// 	}
			// }else{
			// 	throw new Exception('Time string "' . $time . '" in invalid format.');
			// }
		}
		switch($format){
			case 'decimalHours':
			case 'roundDecimalHours':
				$decimalHours = static::getDecimalHours($hours, $minutes);
				if($format === 'decimalHours'){
					return $decimalHours;
				}else{
					$roundedDecimalHours = static::roundDecimalHours($decimalHours);
					return $roundedDecimalHours;
				}
			break;
			case 'object':
				return new DateInterval("PT{$hours}H{$minutes}M");
			break;
		}
	}
	/*
	Convert a number of hours and minutes to a decimal equivalent.
	Arguments:
		hoursOrInterval(Integer|DateInterval): If a DateInterval, will pull hours and minutes from that.  Otherwise this is the hours component.
		minutes(Integer): If not passing a DateInterval, this is the minutes component
	Returns(Double)
	*/
	static public function getDecimalHours($hoursOrInterval, $minutes = 0){
		if(is_object($hoursOrInterval)){
			$hours = $hoursOrInterval->h;
			$minutes = $hoursOrInterval->i;
		}else{
			$hours = $hoursOrInterval;
		}
		return $hours + ($minutes / 60);
	}
	static public function roundDecimalHours($hours, $precision = 'quarter'){
		switch($precision){
			case 'quarter':
				return round($hours * 4) / 4;
			break;
		}
	}

	/*=====
	==instance
	=====*/
	public function __construct($rate = null){
		if(isset($rate)){
			$this->rate = $rate;
		}
	}
	public function getSheetInvoiceAmounts(Sheet $sheet){
		$string = '';
		foreach($sheet->getPeriods() as $period){
			$hours = TimeClockCalculator::calculateDiffs($period->getTimes(), 'roundDecimalHours');
			if($hours){
				$string .= '- ' . $hours . ' hours ($' . number_format($hours * $this->rate, 2) . ')';
				$days = $period->getDays();
				if($days && count($days)){
					$string .= " for period {$days[0]->getDate()}";
					if(count($days) > 1){
						$string .= "-{$days[count($days) - 1]->getDate()}";
					}
				}
				$string .= "\n";
			}
		}
		return $string;
	}
}
