<?php

namespace Scaleplan\Model\Exceptions;

use function Scaleplan\Translator\translate;

/**
 * Class PropertyNotFoundException
 *
 * @package Scaleplan\Model\Exceptions
 */
class ModelToStringConvertingException extends ModelException
{
    public const MESSAGE = 'model.serialize-error';
    public const CODE = 500;

    /**
     * ModelToStringConvertingException constructor.
     *
     * @param string $className
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     *
     * @throws \ReflectionException
     * @throws \Scaleplan\DependencyInjection\Exceptions\ContainerTypeNotSupportingException
     * @throws \Scaleplan\DependencyInjection\Exceptions\DependencyInjectionException
     * @throws \Scaleplan\DependencyInjection\Exceptions\ParameterMustBeInterfaceNameOrClassNameException
     * @throws \Scaleplan\DependencyInjection\Exceptions\ReturnTypeMustImplementsInterfaceException
     */
    public function __construct(
        string $className,
        string $message = '',
        int $code = 0,
        ?\Throwable $previous = null
    )
    {
        parent::__construct(
            translate($message, ['class' => $className,])
                ?: $message
                ?: translate(static::MESSAGE, ['class' => $className,])
                ?: static::MESSAGE,
            $code ?: static::CODE,
            $previous
        );
    }
}
