<?php

namespace Scaleplan\Model\Exceptions;

/**
 * Class PropertyNotFoundException
 *
 * @package Scaleplan\Model\Exceptions
 */
class PropertyNotFoundException extends ModelException
{
    public const MESSAGE = 'Свойство :field не найдено.';
    public const CODE = 404;

    /**
     * PropertyNotFoundException constructor.
     *
     * @param string $propertyName
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(
        string $propertyName,
        string $message = '',
        int $code = 0,
        ?\Throwable $previous = null
    )
    {
        parent::__construct(
            str_replace(':field', $propertyName, $message ?: static::MESSAGE),
            $code ?: static::CODE,
            $previous
        );
    }
}
