<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

use App\model\CsvTransaction;
use App\model\CsvTransactionFactory;

use DateTimeImmutable;

/**
 * CsvTransactionFactoryのテストクラス
 */
class CsvTransactionFactoryTest extends TestCase {

	/**
	 * CsvTransactionFactory::create(array)メソッドのテストケース
	 * @param $target 読み込んだデータファイルの１行分のCSVレコード
	 * @param $expected 期待されるCsvTransactionオブジェクト
	 */
	#[Test]
	#[DataProvider("createProvider")]
	function testCreate(
		array $target,
		CsvTransaction $expected
	):void {
		// execcute
		$actual = CsvTransactionFactory::create($target);
		// verify
		$this->assertSame($expected->toCanonicalString(), $actual->toCanonicalString());
	}

	/**
	 * CsvTransactionFactory::create(array)のテストメソッドにテストパラメータを提供する
	 */
	static function createProvider():array {
		return [
			"[引出取引の場合] 預入金額は0円として作成される"
				=> [
					array("20260322000010","2026.03.22","17510","","2466118","ＰＥ　シヤカイホケンリヨウトウ＊"),
					new CsvTransaction(20260322000010,new DateTimeImmutable("2026-03-22"),17510,0,2466118,"ＰＥ　シヤカイホケンリヨウトウ＊")
				],
			"[預入取引の場合] 引出金額は0円として作成される"
				=> [
					array("20260319000010","2026.03.19","","375297","2503848","還付金　ホンゴウゼイムシヨ"),
					new CsvTransaction(20260319000010,new DateTimeImmutable("2026-03-19"),0,375297,2503848,"還付金　ホンゴウゼイムシヨ")
				]
		];
	}
}