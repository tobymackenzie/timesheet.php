<?php
use PHPUnit\Framework\TestCase;
use TJM\PHPTimesheet\Sheet;

class SheetTest extends TestCase{
	public function testConstructStrings(){
		$sheets = [
			[
				'file'=> 'period1.txt',
				'hours'=> 14.5,
			],
			[
				'file'=> 'sheet1.txt',
				'hours'=> 39.5,
			],
		];
		foreach($sheets as $config){
			$sheet = new Sheet(file_get_contents(__DIR__ . '/resources/' . $config['file']));
			$this->assertEquals($config['hours'], $sheet->getHours(), 'Hours must match calculated value based on times parsed from file');
		}
	}
}
