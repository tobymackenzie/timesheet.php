<?php
use PHPUnit\Framework\TestCase;
use TJM\PHPTimesheet\Day;

class DayTest extends TestCase{
	public function testConstructStrings(){
		$days = Array(
			Array(
				'date'=> '210527',
				'hours'=> 2,
				'location'=> 'Home',
				'string'=> '210527: 1159-1259,1359-1459: (@Home)',
			)
		);
		foreach($days as $config){
			$day = new Day($config['string']);
			$this->assertEquals($config['date'], $day->getDate(), 'Date must match value parsed from string');
			$this->assertEquals($config['hours'], $day->getHours(), 'Hours must match calculated value based on times parsed from string');
			$this->assertEquals($config['location'], $day->getLocation(), 'Location must match value parsed from string');
		}
	}
}
