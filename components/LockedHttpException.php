<?php
namespace app\components;

use yii\web\HttpException;

/**
 * LockedException represents a "Locked" HTTP exception with status code 423.
 *
 * @see https://datatracker.ietf.org/doc/html/rfc4918
 * @author Loris Tissino
 */

class LockedHttpException extends HttpException
{
    /**
     * Constructor.
     * @param string $message error message
     * @param int $code error code
     * @param \Exception $previous The previous exception used for the exception chaining.
     */
    public function __construct($message = null, $code = 0, \Exception $previous = null)
    {
        parent::__construct(423, $message, $code, $previous);
    }
}
