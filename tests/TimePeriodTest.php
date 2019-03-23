<?php
use PHPUnit\Framework\TestCase;
use TJM\PHPTimesheet\TimePeriod;

class TimePeriodTest extends TestCase{
	public function testConstruct(){
		$periods = Array(
			Array(
				'end'=> '1500'
				,'start'=> '1159'
			)
			,Array(
				'end'=> '1003'
				,'start'=> '0900'
			)
			// ,new TimePeriod("0900-1000-1200")
			// ,new TimePeriod("0900-")
		);
		foreach($periods as $config){
			$range = $config['start'] . '-' . $config['end'];
			$period = new TimePeriod($range);
			$this->assertEquals($config['start'], $period->getStart(), 'Passed start should match instance start');
			$this->assertEquals($config['end'], $period->getEnd(), 'Passed end should match instance end');
			$this->assertEquals($range, $period->getRange(), 'Construction range should match instance range');
		}
	}
	public function testDecimalLength(){
		$periods = Array(
			Array(
				'range'=> '1000-1100'
				,'length'=> 1.0
			)
			,Array(
				'range'=> '1100-1200'
				,'length'=> 1.0
			)
			,Array(
				'range'=> '1100-1100'
				,'length'=> 0.0
			)
			,Array(
				'range'=> '1159-1259'
				,'length'=> 1.0
			)
			,Array(
				'range'=> '1159-1229'
				,'length'=> 0.5
			)
			,Array(
				'range'=> '1159-1228'
				,'length'=> 0.48333333333333334
			)
			,Array(
				'range'=> '1159-1828'
				,'length'=> 6.48333333333333334
			)
		);
		foreach($periods as $config){
			$period = new TimePeriod($config['range']);
			$this->assertEquals($period->getLength('decimal'), $config['length'], "Length for range {$config['range']} should be as expected.");
		}
	}
}
