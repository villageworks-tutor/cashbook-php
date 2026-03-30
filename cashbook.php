<?php
require_once __DIR__ . "/vendor/autoload.php";


use App\io\CsvLoader;
use App\io\CsvWriter;
use App\io\exception\NoLoadedTransactionException;
use App\service\CsvService;
use App\utils\PathResolver;

function main(?array $argv = null) {

	$input = $argv[1] ?? "data/input/sample_00.csv";
	$output = PathResolver::resolveOutputPath($input);

	if (!file_exists($input)) {
		fwrite(STDERR, "ファイルが見つかりません：{$input}");
		exit(1);
	}

	echo "処理開始：{$input}\n";

	$service = new CsvService(new CsvLoader(), new CsvWriter($output));
	$service->execute($input, $output);

	echo "処理完了：{$output}\n";

}

main($argv);
