<?php
namespace Tests\model;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

use App\model\CsvTransaction;

use DateTimeImmutable;

/**
 * CsvTransactionクラスのテストクラス
 */
class CsvTransactionTest extends TestCase {

	/** テスト対象クラス：system under test */
	private CsvTransaction $sut;

	protected function setUp():void {
		// setup
		$this->sut = new CsvTransaction();
	}

	/**
	 * CsvTransaction::toArray()メソッドのテストメソッド
	 */
	#[Test]
	function testToArray():void {
		// setup
		$this->sut = new CsvTransaction(
										id: 220,
										date: new DateTimeImmutable("1966/9/7"),
										withdraw: 2000,
										deposit: 0,
										balance: 3000,
										detail: "他行への振り込み"
									);
		$expected = array(220,"1966-09-07",2000,0,3000,"他行への振り込み");
		// execute
		$actual = $this->sut->toArray();
		// varufy
		foreach ($expected as $i => $element) {
			$this->assertEquals($element, $actual[$i]);		
		}
	}

	/**
	 * CsvTransaction::toCsv()メソッドのテストメソッド
	 */
	#[Test]
	function testToCsv():void {
		// setup
		$this->sut = new CsvTransaction(
										id: 220,
										date: new DateTimeImmutable("1966/9/7"),
										withdraw: 2000,
										deposit: 0,
										balance: 3000,
										detail: "他行への振り込み"
									);
		$expected = "220,1966-09-07,2000,0,3000,他行への振り込み";
		// execute
		$actual = $this->sut->toCsv();
		// verify
		$this->assertEquals($expected, $actual);
	}

	/**
	 * CsvTransaction::getDateAsString()メソッドのテストメソッド
	 * @param $format   取得する日付の日付フォーマット
	 * @param $expected 期待される日付リテラル
	 */
	#[Test]
	#[DataProvider("getDateAsStringProvider")]
	function testGetDateAsString($format, $expected):void {
		// setup
		$this->sut = new CsvTransaction(
										id: 1,
										date: new DateTimeImmutable("2000-01-01"),
										withdraw: 2000,
										deposit: 2000,
										balance: 2000,
										detail: ""
									);
		// execute
		$actual = $this->sut->getDateAsString($format);
		// verify
		$this->assertSame($expected, $actual);
	}

	/**
	 * CsvTransaction::getDateAsString()メソッドのテストパラメータを提供する
	 * @reuturn テストパラメータ配列
	 */
	static function getDateAsStringProvider():array {
		return [
			"[フォーマット指定:1] 書式「Y-m-d」を指定して日付文字列を取得できる"   => ["Y-m-d", "2000-01-01"],
			"[フォーマット指定:2] 書式「Ymd」を指定して日付文字列を取得できる"     => ["Ymd", "20000101"],
			"[フォーマット未指定] 書式を指定しない場合は「Y-m-d」形式で取得できる" => [null, "2000-01-01"]
		];
	}
	
}
