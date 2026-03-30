<?php
namespace Tests\io;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

use Tests\io\CsvLoaderEx;

/**
 * CsvLoaderのprotectedメソッドテスト用テストクラス
 */
class CsvLoaderExTest extends TestCase {
	
	/** テスト対象クラス： */
	private CsvLoaderEx $sut;

	protected function setUp():void {
		$this->sut = new CsvLoaderEx();
	}

	/**
	 * CsvLoader::isValidDataLine(array)メソッドのテストケース
	 * @param $target   テスト用配列
	 * @param $expected 結果の期待値
	 */
	#[Test]
	#[DataProvider("isValidDataLineProvider")]
	function testisValidDataLine(
		array $target,
		bool $expected
	):void {
		// execute
		$actual = $this->sut->isValidDataLIne($target);
		// verify
		$this->assertSame($expected, $actual);
	}

	/**
	 * CsvLoader::isValidDataLine(array)メソッドのテストパラメータを提供する
	 * @return テストパラメータ配列
	 */
	static function isValidDataLineProvider():array {
		return [
			"[妥当な配列] 配列のサイズは6でかつ先頭要素はtrueである"
				=> [
					array(20260105000010,2026-01-05,20000,0,2541655,"ＡＴＭ－１７２５７８－１５６８７３"),
					true
				],
			"[妥当ではない配列:1] 配列のサイズが6未満の場合はfalseである"
				=> [
					array("※処理のタイミングにより、窓口で発行する証書や通帳との差異がある場合がございます。"),
					false
				],
			"[妥当ではない配列:2] 先頭要素が数字列ではない場合はfalseである"
				=> [
					array("照会期間終了日付","2026.01.31"),
					false
				],
			"[妥当ではない配列:3] 空配列の場合はfalseである"
				=> [
					array(),
					false
				]
		];
	}


}