<?php

namespace Scaleplan\Model\Exceptions;

use function Scaleplan\Translator\translate;

/**
 * Class PropertyNotFoundException
 *
 * @package Scaleplan\Model\Exceptions
 */
class PropertyNotFoundException extends ModelException
{
    public const MESSAGE = 'model.property-not-found';
    public const CODE = 404;

    /**
     * PropertyNotFoundException constructor.
     *
     * @param string $propertyName
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
        string $propertyName,
        string $message = '',
        int $code = 0,
        ?\Throwable $previous = null
    )
    {
        parent::__construct(
            translate($message, ['field' => $propertyName,])
                ?: $message
                ?: translate(static::MESSAGE, ['field' => $propertyName,])
                ?: static::MESSAGE,
            $code ?: static::CODE,
            $previous
        );
    }
}
