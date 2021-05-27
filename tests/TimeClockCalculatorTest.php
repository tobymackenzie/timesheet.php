<?php
use PHPUnit\Framework\TestCase;
use TJM\PHPTimesheet\Sheet;
use TJM\PHPTimesheet\TimeClockCalculator;

class TimeClockCalculatorTest extends TestCase{
	public function testSheetInvoiceAmounts(){
		// $this->assertTrue(true);
		// return;
		$sheets = [
			[
				'file'=> 'period1.txt',
				'expect'=> "- $145.00 for period 210527-210529\n",
			],
			[
				'file'=> 'sheet1.txt',
				'expect'=> "- $250.00 for period 200527-200531\n- $145.00 for period 210527-210529\n",
			],
		];
		foreach($sheets as $config){
			$calc = new TimeClockCalculator(10);
			$sheet = new Sheet(file_get_contents(__DIR__ . '/resources/' . $config['file']));
			$this->assertEquals($config['expect'], $calc->getSheetInvoiceAmounts($sheet));
		}
	}
}
