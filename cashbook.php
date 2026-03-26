<?php
require_once __DIR__ . "/vendor/autoload.php";


use App\io\CsvLoader;
use App\io\CsvWriter;
use App\io\excception\NoLoadedTransactionException;

$input = $argv[1] ?? "data/input/sample_00.csv";
$output = str_replace("input", "output", $input);

if (!file_exists($input)) {
	fwrite(STDERR, "ファイルが見つかりません：{$input}");
	exit(1);
}

echo "処理開始：{$input}\n";
try {
	$loader = new CsvLoader();
	$transactionList = $loader->load($input);
} catch (NoLoadedTransactionException $e) {
	throw \RuntimeException("取引CSVファイルの読み込み時に問題が発生しました\n" . $e->getMessage(), $e);
}
$writer = new CsvWriter($output);
$writer->out($transactionList);

echo "処理完了：{$output}\n";
exit(0);

