<?php

namespace Scaleplan\Model\Exceptions;

/**
 * Class PropertyNotFoundException
 *
 * @package Scaleplan\Model\Exceptions
 */
class ModelToStringConvertingException extends ModelException
{
    public const MESSAGE = ':class model not converted to string.';
    public const CODE = 500;

    /**
     * PropertyNotFoundException constructor.
     *
     * @param string $className
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(
        string $className,
        string $message = '',
        int $code = 0,
        ?\Throwable $previous = null
    )
    {
        parent::__construct(
            str_replace(':class', $className, $message ?: static::MESSAGE),
            $code ?: static::CODE,
            $previous
        );
    }
}
