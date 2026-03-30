<?php
namespace App\utils;

/**
 * ファイルパスを生成するユーティリティクラス
 */
class PathResolver {

	/**
	 * 入力ファイルのパスから出力先ディレクトリのパスを生成する
	 * @param  $inputPath 入力ファイルのパス
	 * @return 出力先ディレクトリパス
	 */
	public static function resolveOutputPath(string $inputPath):string {
		$outputDir = str_replace("input", "output", $inputPath);
		return $outputDir;
	}

}