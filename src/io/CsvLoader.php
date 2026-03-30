<?php
namespace App\io;

use App\io\exceptions\NoLoadedTransactionException;
use App\io\exceptions\FileNotOpenedException;
use App\model\CsvTransaction;
use App\model\CsvTransactionFactory;
use App\model\exceptions\InvalidTransactionException;

/**
 * 取引CSVファイルを読み込むクラス
 */
class CsvLoader {

	/**
	 * 指定されたパスの取引CSVファイルを読み込む
	 * @param CsvTransaction[] CsvTransactionオブジェクトのリスト
	 */
	public function load(string $path):array {

		// 指定されたファイルの存在確認：存在しない場合は例外を発生
		if (!file_exists($path)) throw new FileNotOpenedException("ファイルを開けません：{$path}");
		$input = fopen($path, "r");

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
				throw new NoLoadedTransactionException("取引が読み込まれていません：{$path}");
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