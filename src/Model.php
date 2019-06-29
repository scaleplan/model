<?php

namespace Scaleplan\Model;

use Scaleplan\Helpers\NameConverter;
use Scaleplan\InitTrait\InitTrait;
use Scaleplan\Model\Exceptions\ModelToStringConvertingException;
use Scaleplan\Model\Exceptions\OnlyGettersSupportingException;
use Scaleplan\Model\Exceptions\PropertyNotFoundException;

/**
 * Class Model
 *
 * @package Scaleplan\Model
 */
class Model
{
    use InitTrait;

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
        $this->attributes = $this->initObject($attributes);
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

    /**
     * @return array
     */
    public function toArray() : array
    {
        $rawArray = (array)$this;
        $replaces = [static::class => '', '*' => ''];
        foreach (class_parents(static::class) as $parent) {
            $replaces[$parent] = '';
        }

        foreach ($rawArray as $key => $value) {
            $newKey = trim(strtr($key, $replaces));
            $rawArray[$newKey] = $value;
            unset($rawArray[$key]);
        }

        unset($rawArray['attributes']);

        $array = [];
        foreach ($rawArray as $property => $value) {
            $methodName = 'get' . ucfirst($property);
            $array[NameConverter::camelCaseToSnakeCase($property)]
                = is_callable([$this, $methodName]) ? $this->$methodName() : $value;
        }

        return array_merge($this->attributes, $array);
    }

    /**
     * @return string
     *
     * @throws ModelToStringConvertingException
     */
    public function __toString() : string
    {
        if (!$json = \json_encode($this->toArray(), JSON_OBJECT_AS_ARRAY | JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES
            | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION)) {
            throw new ModelToStringConvertingException(static::class);
        }

        /** @var string $json */
        return $json;
    }
}
