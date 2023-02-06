<?php


namespace Centras\Exceptions;

use Exception;

/**
 * SPL Exceptions LIST https://www.php.net/manual/en/spl.exceptions.php
 *
 * BadFunctionCallException
 * BadMethodCallException
 * DomainException
 * InvalidArgumentException
 * LengthException
 * LogicException
 * OutOfBoundsException
 * OutOfRangeException
 * OverflowException
 * RangeException
 * RuntimeException
 * UnderflowException
 * UnexpectedValueException
 *
 */

class CentrasException extends Exception
{
    /**
     * CentrasException constructor.
     * @param $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
