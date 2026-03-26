<?php
namespace App\model;

use DateTimeImmutable;

/**
 * １回の取引に関わる情報を格納管理するクラス
 */
class CsvTransaction {

	/**
	 * フィールド
	 */
	private int $id;                  // 取引明細番号
	private ?DateTimeImmutable $date; // 取引日付
	private int $withdraw;            // 引出金額
	private int $deposit;             // 預入金額
	private int $balance;             // 差引残高
	private string $detail;           // 取引内容

	/**
	 * コンストラクタ
	 * @param $id       取引明細番号
	 * @param $date     取引日付
	 * @param $withdraw 引出金額
	 * @param $deposit  預入金額
	 * @param $balance  差引残高
	 * @param $detail   取引内容
	 */
	public function __construct(
		int $id = 0,
		?DateTimeImmutable $date = null,
		int $withdraw = 0,
		int $deposit = 0,
		int $balance = 0,
		string $detail = ""
	) {
		$this->id = $id;
		$this->date = $date;
		$this->withdraw = $withdraw;
		$this->deposit = $deposit;
		$this->balance = $balance;
		$this->detail = $detail;
	}

	public function getId():int {
		return $this->id;
	}

	public function setDate(mixed $date):void {
		if ($date instanceOf DateTimeImmutable) {
			$this->date = $date;
		} else if (is_string($date)) {
			$this->date = new DateTimeImmutable($date);
		} else {
			throw new InvalidArgumentException("引数の型が無効です。");
		}
	}

	public function getDate():DateTimeImmutable {
		return $this->date;
	}

	public function getDateAsString(?string $format = null):string {
		if ($format === null) {
			$format = "Y-m-d";
		}
		if ($this->date === null) {
			return "0000-00-00";
		}
		return $this->date->format($format);
	}

	public function setWithdraw(int $withdraw):void {
		$this->withdraw = $withdraw;
	}

	public function getWithdraw():int {
		return $this->withdraw;
	}

	public function getWithdrawAsString():string {
		return (string) $this->withdraw;
	}

	public function setDeposit(int $deposit):void {
		$this->deposit = $deposit;
	}

	public function getDeposit():int {
		return $this->deposit;
	}

	public function getDepositAsString():string {
		return (string) $this->deposit;
	}

	public function setDetail(string $detail):void {
		$this->detail = $detail;
	}

	public function getDetail():string {
		return $this->detail;
	}

	/**
	 * フィールド値を元にCSV文字列に変換する
	 * @return 各フィールド値のCSV文字列
	 */
	public function toCsv():string {
		$csvString = implode(",", $this->toCanonicalArray());
		return $csvString;
	}

	/**
	 * フィールド値を要素とする配列を返す
	 * @return フィールド値を要素とする配列
	 */
	public function toArray():array {
		$cols = [];
		$canonicalArray = $this->toCanonicalArray();
		foreach ($canonicalArray as $col) {
			$cols[] = $col;
		}
		return $cols;
	}

	/**
	 * テストおよび比較処理のための正規文字列表現を返す。
	 *
	 * このメソッドは以下の用途を想定している：
	 * - PHPUnit による assertion
	 * - スナップショット的な比較処理
	 *
	 * 出力形式は安定性を前提とするため、軽率に変更してはならない。
	 * 人間向けの表示が必要な場合は toString() を使用すること。
	 */
	public function toCanonicalString(): string {
			return json_encode(
					$this->toCanonicalArray(),
					JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR
			);
	}
	
	/**
	 * 人間が読むことを目的とした文字列表現を返す。
	 *
	 * 主な用途：
	 * - ログ出力
	 * - デバッグ
	 *
	 * 表示形式は自由に変更される前提であり、
	 * assertion や機械的な比較処理には使用してはならない。
	 */
	public function toString():string {
		$canonicalArray = $this->toCanonicalArray();
		$output  = "";
		$output .= "CsvTransaction = [";
		$output .= "id = "       . $canonicalArray["id"] . ", ";
		$output .= "date = "     . $canonicalArray["date"] . ", ";
		$output .= "withdraw = " . $canonicalArray["withdraw"] . ", ";
		$output .= "deposit = "  . $canonicalArray["deposit"] . ", ";
		$output .= "balance = "  . $canonicalArray["balance"] . ", ";
		$output .= "detail = "   . $canonicalArray["detail"];
		$output .= "]";
		return $output;
	}

	/**
	 * この DTO の正規（カノニカル）表現。
	 *
	 * - シリアライズおよび比較処理における唯一の基準となる表現
	 * - toCanonicalString() や jsonSerialize() などから内部的に利用される
	 * - 配列としての無秩序な利用を防ぐため、意図的に private にしている
	 *
	 * この DTO を配列として外部に公開する必要が生じた場合は、
	 * toArray() を public にするのではなく、
	 * 役割に応じた専用メソッドを新たに定義すること。
	 */
	public function toCanonicalArray(): array {
			return [
					'id' => $this->id,
					'date' => $this->getDateAsString(),
					'withdraw' => $this->withdraw,
					'deposit' => $this->deposit,
					'balance' => $this->balance,
					'detail' => $this->detail
			];
	}

}