<?php
namespace Tests\service;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

use App\service\CsvService;
use App\service\exceptions\CsvServiceException;
use App\io\CsvLoader;
use App\io\CsvWriter;
use App\io\exceptions\NoLoadedTransactionException;
use App\utils\PathResolver;

class CsvService_NoLoadedExceptionTest extends TestCase {

	/** テスト対象クラス：system under test */
	private CsvService $sut;

	/** テスト補助変数 */
	private string $inputFilePath;
	private string $outputFilePath;

	protected function setUp():void {
		// テスト用一時ファイルの追加
		$this->inputFilePath = __DIR__."/../../data/input/csv_test_temp.csv";
		$this->outputFilePath = PathResolver::resolveOutputPath($this->inputFilePath);
		$fHandle = fopen($this->inputFilePath, "w");
		fclose($fHandle);
		// テスト対象クラスのインスタンス化
		$this->sut = new CsvService(new CsvLoader(), new CsvWriter($this->outputFilePath));
	}

	protected function tearDown():void {
		// テスト用一時ファイルの削除
		unlink($this->inputFilePath);
	}

	/**
	 * 発生した例外がNoLoadedTransaction例外であることを確認するテストケース
	 */
	#[Test]
	function testExecute_with_NoLoadedTransactionException():void {
		try {
			$this->sut->execute($this->inputFilePath, $this->outputFilePath);
		} catch (CsvServiceException $e) {
			// previousの存在確認
			$this->assertNotNull($e->getPrevious());
			// NoLoadedTransactionExceptionであることの確認
			$this->assertInstanceOf(NoLoadedTransactionException::class, $e->getPrevious());
			// 例外メッセージの確認
			$this->assertStringContainsString("取引が読み込まれていません：{$this->inputFilePath}", $e->getPrevious()->getMessage());
		}
	}

}