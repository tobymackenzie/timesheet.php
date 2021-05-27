<?php
use PHPUnit\Framework\TestCase;
use TJM\PHPTimesheet\PayPeriod;

class PayPeriodTest extends TestCase{
	public function testConstructStrings(){
		$periods = Array(
			Array(
				'dayCount'=> 3,
				'file'=> 'period1.txt',
				'hours'=> 14.5,
			)
		);
		foreach($periods as $config){
			$period = new PayPeriod(file_get_contents(__DIR__ . '/resources/' . $config['file']));
			$this->assertEquals($config['hours'], $period->getHours(), 'Hours must match calculated value based on times parsed from file');
			$this->assertEquals($config['dayCount'], count($period->getDays()), 'Day count must match number of days specified in file');
		}
	}
}
