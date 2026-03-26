<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

use App\io\CsvLoader;
use App\model\CsvTransaction;

use DateTimeImmutable;

class CsvLoaderTest extends TestCase {

	private CsvLoader $sut;

	function setUp():void {
		$this->sut = new CsvLoader();
	}

	#[Test]
	function testLoad():void {
		// setup
		$target = dirname(__DIR__) . "/data/input/sample_00.csv";
		$expectedList = [];
		$expectedList[] = new CsvTransaction(20260130000020, new DateTimeImmutable("2026-01-30"),110,0,2364265,"振込手数料");
		$expectedList[] = new CsvTransaction(20260130000010, new DateTimeImmutable("2026-01-30"),4000,0,2364375,"ネツト　ムラ　フミヒコ");
		$expectedList[] = new CsvTransaction(20260128000020, new DateTimeImmutable("2026-01-28"),220,0,2368375,"手数料");
		$expectedList[] = new CsvTransaction(20260128000010, new DateTimeImmutable("2026-01-28"),40000,0,2368595,"テイケイ　ジヨウホクシンキン");
		$expectedList[] = new CsvTransaction(20260127000010, new DateTimeImmutable("2026-01-27"),20000,0,2408595,"ＡＴＭ（２３６）");
		$expectedList[] = new CsvTransaction(20260119000060, new DateTimeImmutable("2026-01-19"),110,0,2428595,"手数料");
		$expectedList[] = new CsvTransaction(20260119000050, new DateTimeImmutable("2026-01-19"),20000,0,2428705,"テイケイ　ユウチヨ");
		$expectedList[] = new CsvTransaction(20260119000040, new DateTimeImmutable("2026-01-19"),110,0,2448705,"振込手数料");
		$expectedList[] = new CsvTransaction(20260119000030, new DateTimeImmutable("2026-01-19"),30000,0,2448815,"ネツト　ムラ　フミヒコ");
		$expectedList[] = new CsvTransaction(20260119000020, new DateTimeImmutable("2026-01-19"),25220,0,2478815,"ＰＥ　ブンキヨウクコクミンケ＊");
		$expectedList[] = new CsvTransaction(20260119000010, new DateTimeImmutable("2026-01-19"),17510,0,2504035,"ＰＥ　シヤカイホケンリヨウトウ＊");
		$expectedList[] = new CsvTransaction(20260110000020, new DateTimeImmutable("2026-01-10"),110,0,2521545,"時間外手数料");
		$expectedList[] = new CsvTransaction(20260110000010, new DateTimeImmutable("2026-01-10"),20000,0,2521655,"テイケイＶＩＥＷ３５２０８");
		$expectedList[] = new CsvTransaction(20260105000010, new DateTimeImmutable("2026-01-05"),20000,0,2541655,"ＡＴＭ－１７２５７８－１５６８７３");

		// execute
		$actual = $this->sut->load($target);
		// verify
		foreach ($expectedList as $i => $expected) {
			$this->assertSame($expected->toCanonicalString(), $actual[$i]->toCanonicalString());
		}
	}

}
