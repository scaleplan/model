<?php

namespace Scaleplan\Model;

use Scaleplan\Helpers\NameConverter;
use Scaleplan\Model\Exceptions\OnlyGettersSupportingException;
use Scaleplan\Model\Exceptions\PropertyNotFoundException;

/**
 * Class Model
 *
 * @package Scaleplan\Model
 */
class Model
{
    /**
     * @var array
     */
    protected $attributes;

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
     * @param array $args
     *
     * @return mixed
     *
     * @throws OnlyGettersSupportingException
     * @throws PropertyNotFoundException
     */
    public function __call(string $name, array $args)
    {
        if (strpos($name, 'get') !== 0) {
            throw new OnlyGettersSupportingException();
        }

        $planeAttributeName = lcfirst(str_replace('get', '', $name));
        $snakeAttributeName = NameConverter::camelCaseToSnakeCase(str_replace('get', '', $name));
        if (array_key_exists($planeAttributeName, $this->attributes)) {
            return $this->attributes[$planeAttributeName];
        }

        if (array_key_exists($snakeAttributeName, $this->attributes)) {
            return $this->attributes[$snakeAttributeName];
        }

        throw new PropertyNotFoundException($planeAttributeName);
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
