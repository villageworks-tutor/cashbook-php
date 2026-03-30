<?php
namespace App\service;

use App\io\CsvLoader;
use App\io\CsvWriter;
use App\io\exceptions\NoLoadedTransactionException;
use App\service\exceptions\CsvServiceException;

/**
 * CSVファイルを読み込んで処理を実行するServiceクラス
 */
class CsvService {

	private CsvLoader $loader;
	private CsvWriter $writer;

	public function __construct(CsvLoader $loader, CsvWriter $writer) {
		$this->loader = $loader;
		$this->writer = $writer;
	}

	/**
	 * CSVファイルを読み込んで出力先にCSV形式で出力する
	 * @param $input  入力ファイルのパス
	 * @param $output 出力先ファイルパス
	 */
	public function execute(string $input, string $output):void {

		try {
			// CSVファイルの読込み
			$transactionList = $this->loader->load($input);
			// 読み込んだCSVデータを出力
			$this->writer->out($transactionList);
		} catch (FileNotOpenedException | NoLoadedTransactionException $e) {
			throw new CsvServiceException("取引CSVファイルの読み込み時に問題が発生しました：{$e->getMessage()}\n", $e);
		} catch (\Throwable $e) {
			throw new CsvServiceException("予期せぬエラーが発生しました：{$e->getMessage()}\n", $e);
		}

	}

}