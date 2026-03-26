<?php
namespace App\io;

use App\model\CsvTransaction;
use App\model\CsvTransactionFactory;
use App\model\exceptions\InvalidTransactionException;
use App\io\exceptions\NoLoadedTransactionException;

/**
 * 取引CSVファイルを読み込むクラス
 */
class CsvLoader {

	/**
	 * 指定されたパスの取引CSVファイルを読み込む
	 * @param CsvTransaction[] CsvTransactionオブジェクトのリスト
	 */
	public function load(string $path):array {

		$input = fopen($path, "r");
		// 指定されたファイルが存在しない場合
		if ($input === false) throw new \RuntimeException("ファイルを開けません：{$path}");

		try {
			$transactions = [];
			while (($line = fgetcsv($input)) !== false) {
				if (!$this->isValidDataLine($line)) continue;
				try {
					$transaction = CsvTransactionFactory::create($line);
					$transactions[] = $transaction;
				} catch (InvalidTransactionException $e) {
					continue;
				}
			}

			if (count($transactions) === 0) {
				throw new NoLoadedTransactionException("取引が読み込まれていません：{$input}");
			}

			return $transactions;

		} finally {
			if (is_resource($input)) {
				fclose($input);
			}
		}

	}

	/**
	 * 読み込んである行が妥当な取引レコードであるかどうかを判定する
	 * 
	 * 判定基準：行から生成される配列について以下の要件を満たすかどうか
	 * 		1. nullでないこと
	 * 		2. 6要素から構成されていること
	 * 
	 * @param  $target 判定対象行から生成される配列
	 * @return 判定基準を満たしている場合はtrue、それ以外はfalse
	 */
	protected function isValidDataLine(array $target):bool {
		return (count($target) === 6);
	}

}