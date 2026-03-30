<?php
namespace Tests\service;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

use App\service\CsvService;
use App\service\exceptions\CsvServiceException;
use App\io\CsvLoader;
use App\io\CsvWriter;

class CsvServiceTest extends TestCase {

	/** テスト対象クラス：system under test */
	private CsvService $sut;

	/**
	 * 例外を発生させるテストケース
	 */
	#[Test]
	function testExecute_with_Exception():void {
		//setup
		$input = "data/input/csv_test_temp.csv";
		$output = "data/output/csv_test_temp.csv";
		$this->sut = new CsvService(new CsvLoader(), new CsvWriter($output));
		$this->expectException(CsvServiceException::class);
		// execute & verify
		$this->sut->execute($input, $output);
	}

}