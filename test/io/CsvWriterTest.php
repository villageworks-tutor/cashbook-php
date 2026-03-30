<?php
namespace Tests\io;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

use App\io\CsvWriter;
use App\io\exceptions\FileNotOpenedException;
use App\model\CsvTransaction;

use DateTimeImmutable;

/**
 * CsvWriterのテストクラス
 */
class CsvWriterTest extends TestCase {

	/** テスト対象クラス：system under test */
	private CsvWriter $sut;

	/** テスト補助変数 */
	private string $tempDir;
	private string $outputPath;

	/**
	 * テストの前準備
	 */
	protected function setUp():void {
		// テスト用一時ディレクトリの作成
		$this->tempDir = __DIR__."/../../data/csv_test_temp";
		if (!is_dir($this->tempDir)) {
			mkdir($this->tempDir, 0777, true);
		}
		// テスト対象クラスのインスタンス化
		$this->outputPath = $this->tempDir."/temp.csv";
		$this->sut = new CsvWriter($this->outputPath);
	}

	/**
	 * テストの後始末
	 */
	protected function tearDown():void {
		// 生成したファイルとテスト用一時ディレクトリの削除
		array_map("unlink", glob($this->tempDir."/*"));
		rmdir($this->tempDir);
	}

	/**
	 * 出力先ファイルに書き込みできない場合に発生する例外のテストケース
	 */
	function testThrowFileNotOpendException_onCsvWriter():void {
		// setup
		$writer = new CsvWriter("data/data/data.csv");
		$target = [];
		$target[] = new CsvTransaction(1, new DateTimeImmutable('2026-03-27'), 1000, 0,0,"陽取引");
		$target[] = new CsvTransaction(2, new DateTimeImmutable('2026-03-28'), 0, 500,0,"闇取引");
		$this->expectException(FileNotOpenedException::class);
		$this->expectExceptionMessageMatches("/ファイルを書き込めません：/");
		// execute & verify
		$writer->out($target);
	}

	/**
	 * CsvWriter::out(CsvTransaction[])メソッドのテストケース
	 * @param $dummy    あらかじめ投入するダミーレコード
	 * @param $target   出力の対象となるCSV取引データ
	 * @param $expected 出力が期待されるCSV取引データ
	 */
	#[Test]
	#[DataProvider("outProvider")]
	function testOut_asCsv(
		string $dummy,
		array $target,
		array $expected
	):void {
		// setup：事前投入ダミーデータの登録
		if (!empty($dummy)) {
			file_put_contents($this->outputPath, $dummy);
		}
		// execute：出力の実行と出力内容の取得
		$this->sut->out($target);
		$actual = file($this->outputPath, FILE_IGNORE_NEW_LINES);
		// verify
		$this->assertFileExists($this->outputPath);  // CSVファイルの存在確認
		$this->assertCount(count($expected), $actual); // 生成されたファイルの行数確認
		// 生成されたCSVデータの内容確認
		foreach ($expected as $i => $row) {
			$this->assertSame($row, $actual[$i]);
		}

	}

	/**
	 * CsvWriterTest::testOut_asCsv()メソッドのテスト用パラメータを提供する
	 * @return テストパラメータ
	 */
	static function outProvider():array {
		return [
			"[CSV出力:1] CSVファイルを書き出す"
				=> [
					"",
					[
            new CsvTransaction(1, new DateTimeImmutable('2026-03-27'), 1000, 0,0,"陽取引"),
            new CsvTransaction(2, new DateTimeImmutable('2026-03-28'), 0, 500,0,"闇取引")
					],
					[
						"1,2026-03-27,1000,0,0,陽取引",
						"2,2026-03-28,0,500,0,闇取引"
					]
				],
			"[CSV出力:2] CSVファイルを上書きする"
				=> [
					"1,2026-03-27,1000,0,0,ダミー取引",
					[
						new CsvTransaction(1024, new DateTimeImmutable('2026-03-27'), 1000, 0, 0, "強引な取引")
					],
					[
						"1024,2026-03-27,1000,0,0,強引な取引"
					]
				]
		];
	}

}