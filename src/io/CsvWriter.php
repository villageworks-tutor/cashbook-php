<?php
namespace App\io;

use App\io\exceptions\FileNotOpenedException;
use App\model\CsvTransaction;
use App\model\CsvTransactionFactory;
use App\utils\PathResolver;

/**
 * 取引内容をファイルに書き込むクラス
 */
class CsvWriter {

	/**
	 * クラスフィールド
	 */
	private string $path; // 出力先ファイルパス

	/**
	 * コンストラクタ
	 */
	public function __construct(string $path) {
		$this->path = $path;
	}

	/**
	 * 取引内容をファイルに書き出す
	 * @param CsvTransaction[] $transactionList 書き出す取引オブジェクトのリスト
	 */
	public function out(array $transactionList):void {
		// 指定されたファイルパスが存在しない場合
		$outputDir = PathResolver::resolveOutputPath($this->path);
		$output = @fopen($outputDir, "w");
		// 指定されたファイルが存在しない場合
		if ($output === false) {
			throw new FileNotOpenedException("ファイルを書き込めません：{$this->path}");
		}

		try {
			foreach ($transactionList as $transaction) {
				fputcsv($output, $transaction->toArray());
			}
		} finally {
			if (is_resource($output)) {	
				fclose($output);
			}
		}
	}

}