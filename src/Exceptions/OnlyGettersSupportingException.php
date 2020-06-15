<?php

namespace Scaleplan\Model\Exceptions;

/**
 * Class OnlyGettersSupportingException
 *
 * @package Scaleplan\Model\Exceptions
 */
class OnlyGettersSupportingException extends ModelException
{
    public const MESSAGE = 'Поддерживаются только геттеры.';
    public const CODE = 406;
}
