<?php

namespace Scaleplan\Model\Exceptions;

/**
 * Class OnlyGettersSupportingException
 *
 * @package Scaleplan\Model\Exceptions
 */
class OnlyGettersSupportingException extends ModelException
{
    public const MESSAGE = 'model.only-getter-supported';
    public const CODE = 406;
}
