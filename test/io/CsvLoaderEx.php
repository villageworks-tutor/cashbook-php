<?php
namespace Tests\io;

use App\io\CsvLoader;

/**
 * CsvLoaderのprotectedスコープのメソッドテスト用拡張クラス
 */
class CsvLoaderEx extends CsvLoader {
	public function isValidDataLine(array $line):bool {
		return parent::isValidDataLine($line);
	}
}