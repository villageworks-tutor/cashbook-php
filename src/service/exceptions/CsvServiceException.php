<?php
namespace App\service\exceptions;

use Exception;
use Throwable;

/**
 * CsvServiceクラスで発生する取引未読込例外（独自例外）
 */
class CsvServiceException extends Exception {

  /**
   * コンストラクタ
   */
  public function __construct(string $message, ?Throwable $previous = null) {
    parent::__construct($message, 0, $previous);
  }
}
