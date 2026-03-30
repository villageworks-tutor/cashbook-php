<?php
namespace Tests\utils;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

use App\utils\PathResolver;

/**
 * CsvWriterのprotectedメソッドテスト用テストクラス
 */
class PathResolverTest extends TestCase {

	#[Test]
	#[DataProvider("resolveOutputPathProvider")]
	function testResolveOutputPath(
		string $target,
		string $expected
	):void {
		// execuute
		$actual = PathResolver::resolveOutputPath($target);
		// verify
		$this->assertSame($expected, $actual);
	}

	static function resolveOutputPathProvider():array {
		return [
			"[入力ファイルパス指定:1] 仕様に基づいた入力ファイルパスを指定"
				=> [
					"data/input/hoge.csv",
					"data/output/hoge.csv"
				],
			"[入力ファイルパス指定:2] 仕様位置に基づいていない入力ファイルパスを指定"
				=> [
					"data/ufj/input/2026/03/27/hoge.csv",
					"data/ufj/output/2026/03/27/hoge.csv"
				],
			"[入力ファイルパス未指定] 入力うファイルパス未指定"
				=> [
					"data/input/sample_00.csv",
					"data/output/sample_00.csv"
				]
		];
	}

}