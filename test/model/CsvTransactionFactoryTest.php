<?php
namespace Tests\model;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

use App\model\CsvTransaction;
use App\model\CsvTransactionFactory;
use App\model\exceptions\InvalidTransactionException;

use DateTimeImmutable;

/**
 * CsvTransactionFactoryのテストクラス
 */
class CsvTransactionFactoryTest extends TestCase {

	/**
	 * CsvTransactionFactory::createメソッドに伴うInvalidTransactionExceptionがスロースのテストケース
	 */
	#[Test]
	#[DataProvider("throwInvalidTransactionExceptionProvider")]
	function testThrowException(
		array $target,
		string $expected
	):void {
		// setup
		$this->expectException(InvalidTransactionException::class);
		$this->expectExceptionMessage($expected);
		// execute & verify
		CsvTransactionFactory::create($target);
	}

	/**
	 * testThrowInvalidTransException用のテストパラメータを提供する
	 * @return テストパラメータ配列
	 */
	static function throwInvalidTransactionExceptionProvider():array {
		return [
			"[不正な取引例外:1] 明細番号が不正な場合：明細番号に「カンマ」が含まれている"
				=> [
					array("30,000", "2026.03.28", 1000, 0, 20000, "ホワイト取引"),
					"取引明細番号が不正です"
				],
			"[不正な取引例外:2] 取引日付の書式が不正な場合：日付区切りがスラッシュ「/」になっている"
				=> [
					array(30000, "2026/03/28", 1000, 0, 20000, "ホワイト取引"),
					"取引日付が不正です"
				],
			"[不正な取引例外:3] 出金金額が不正な場合：金額に通貨記号が含まれている"
				=> [
					array(30000, "2026.03.28", "\1000", 0, 20000, "ホワイト取引"),
					"金額が不正です"
				]
		];
	}

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