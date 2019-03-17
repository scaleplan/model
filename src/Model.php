<?php

namespace Scaleplan\Model;

use Scaleplan\Model\Exceptions\OnlyGettersSupportingException;
use Scaleplan\Model\Exceptions\PropertyNotFoundException;

/**
 * Class Model
 *
 * @package Scaleplan\Model
 */
final class Model
{
    /**
     * @var array
     */
    private $attributes;

    /**
     * TemplateClass constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    /**
     * @param string $name
     *
     * @return mixed
     *
     * @throws OnlyGettersSupportingException
     * @throws PropertyNotFoundException
     */
    public function __call(\string $name)
    {
        if (strpos($name, 'get') !== 0) {
            throw new OnlyGettersSupportingException();
        }

        $attributeName = lcfirst(str_replace('get', '', $name));
        if (!array_key_exists($attributeName, $this->attributes)) {
            throw new PropertyNotFoundException($attributeName);
        }

        return $this->attributes[$attributeName];
    }

    /**
     * @param $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        return array_key_exists($name, $this->attributes);
    }

    /**
     * @param $name
     *
     * @return bool
     */
    public function __unset($name)
    {
        return false;
    }
}
