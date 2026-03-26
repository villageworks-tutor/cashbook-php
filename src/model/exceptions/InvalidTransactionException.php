<?php
namespace App\model\exceptions;

use Exception;
use Throwable;

/**
 * CsvTransaction/CsvTransactionFactoryで発生する不正取引例外（独自例外）
 */
class InvalidTransactionException extends Exception {

  /**
   * コンストラクタ
   */
  public function __construct(string $message, ?Throwable $previous = null) {
    parent::__construct($message, 0, $previous);
  }
}
