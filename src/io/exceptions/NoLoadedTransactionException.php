<?php
namespace App\io\exceptions;

use Exception;
use Throwable;

/**
 * CsvLoaderで発生する取引未読込例外（独自例外）
 */
class NoLoadedTransactionException extends Exception {

  /**
   * コンストラクタ
   */
  public function __construct(string $message, ?Throwable $previous = null) {
    parent::__construct($message, 0, $previous);
  }
}
