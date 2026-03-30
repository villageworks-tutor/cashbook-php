<?php
namespace Tests\io;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

use App\io\CsvLoader;
use App\io\CsvWriter;
use App\io\exceptions\FileNotOpenedException;

class CsvLoaderThrowExceptionTest extends TestCase {

	/** テスト対象クラス：systemm under test */
	private CsvLoader $sut;

	#[Test]
	function testThrowFileNotFoundException_onCsvLoader():void {
		// setup
		$this->sut = new CsvLoader();
		$inputPath = __DIR__."/not/exists/csv_test_dummy.csv";
		$this->expectException(FileNotOpenedException::class);
		$this->expectExceptionMessage("ファイルを開けません：{$inputPath}");
		// execute & verify
		$this->sut->load($inputPath);
	}

}
