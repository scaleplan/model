<?php

namespace Scaleplan\Model\Exceptions;

/**
 * Class OnlyGettersSupportingException
 *
 * @package Scaleplan\Model\Exceptions
 */
class OnlyGettersSupportingException extends ModelException
{
    public const MESSAGE = 'Only getters supporting.';
    public const CODE = 406;
}
