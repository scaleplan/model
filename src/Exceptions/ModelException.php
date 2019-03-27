<?php

namespace Scaleplan\Model\Exceptions;

/**
 * Class ModelException
 *
 * @package Scaleplan\Model\Exceptions
 */
class ModelException extends \Exception
{
    public const MESSAGE = 'Model error.';

    /**
     * ModelException constructor.
     *
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = '', int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message ?: static::MESSAGE, $code, $previous);
    }
}
