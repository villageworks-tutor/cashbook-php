<?php
namespace App\model;

use App\model\exceptions\InvalidTransactionException;

use DateTimeImmutable;

class CsvTransactionFactory {

	/**
	 * 取引オブジェクトを生成する
	 * @param $cols １行分のCSVレコード
	 */
	public static function create(array $cols):CsvTransaction {
		// 取引明細番号の形式チェック
		if (!is_numeric($cols[0])) {
			throw new InvalidTransactionException("取引明細番号が不正です");
		}
		// 日付の形式チェック
		$date = DateTimeImmutable::createFromFormat("Y.m.d", $cols[1]);
		if ($date === false) {
			throw new InvalidTransactionException("取引日付が不正です");
		}
		// 金額の形式チェック
		if (!is_numeric(str_replace(',', '', $cols[2]))) {
			throw new InvalidTransactionException("金額が不正です");
		}

		// 各フィールド値をデータ型を設定
		$id       = (int) $cols[0];
		$date     = DateTimeImmutable::createFromFormat("Y.m.d", $cols[1]);
		$withdraw = self::convertToInt($cols[2]);
		$deposit  = self::convertToInt($cols[3]);
		$balance  = self::convertToInt($cols[4]);
		$detail   = $cols[5];
		// 取引オブジェクトをインスタンス化
		$transaction = new CsvTransaction($id, $date, $withdraw, $deposit, $balance, $detail);

		// 取引オブジェクトを返却
		return $transaction;
	}

	/**
	 * 文字列型を整数型に変換する（空文字列は0に変換）
	 */
	static function convertToInt(string $target):int {
		return (empty($target)) ? 0 : (int) $target;
	}

}