<?php

namespace Scaleplan\Model;

use Scaleplan\Helpers\NameConverter;
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
    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @var bool
     */
    protected $allowMagicSet = false;

    /**
     * TemplateClass constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        foreach ($attributes as $name => &$value) {
            $propertyName = null;
            $propertiesArray = $this->getPropertyArray();

            if (\array_key_exists($name, $propertiesArray)) {
                $propertyName = $name;
            }

            if ($propertyName === null
                && ($prepare = NameConverter::snakeCaseToLowerCamelCase($name))
                && array_key_exists($prepare, $propertiesArray)
            ) {
                $propertyName = $prepare;
            }

            if ($propertyName) {
                $methodName = 'set' . ucfirst($propertyName);
                if (method_exists($this, $methodName)) {
                    $this->{$methodName}($value);
                    continue;
                }

                $this->{$propertyName} = $value;
                continue;
            }

            $this->attributes[$name] = $value;
        }

        unset($value);
    }

    /**
     * @param string $name
     * @param array $args
     *
     * @return void|mixed
     *
     * @throws OnlyGettersSupportingException
     * @throws PropertyNotFoundException
     */
    public function __call(string $name, array $args)
    {
        $propertyName = strtr($name, ['get' => '', 'set' => '']);
        $propertiesArray = $this->toArray();
        $planeAttributeName = lcfirst($propertyName);
        $snakeAttributeName = NameConverter::camelCaseToSnakeCase($propertyName);

        if (strpos($name, 'get') === 0) {
            if (\array_key_exists($planeAttributeName, $propertiesArray)) {
                return $this->{$planeAttributeName};
            }

            if (array_key_exists($snakeAttributeName, $propertiesArray)) {
                return $this->{$snakeAttributeName};
            }

            if (array_key_exists($planeAttributeName, $this->attributes)) {
                return $this->attributes[$planeAttributeName];
            }

            if (array_key_exists($snakeAttributeName, $this->attributes)) {
                return $this->attributes[$snakeAttributeName];
            }

            throw new PropertyNotFoundException($planeAttributeName);
        }

        if (strpos($name, 'set') === 0) {
            if (!$this->allowMagicSet) {
                throw new OnlyGettersSupportingException();
            }

            if (\array_key_exists($planeAttributeName, $propertiesArray)) {
                $this->{$planeAttributeName} = $args[0];
            }

            if (array_key_exists($snakeAttributeName, $propertiesArray)) {
                $this->{$snakeAttributeName} = $args[0];
            }

            $this->attributes[$propertyName] = $args[0];
        }
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
    public function getPropertyArray() : array
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

        return $rawArray;
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        $array = [];
        foreach ($this->getPropertyArray() as $property => $value) {
            $array[NameConverter::camelCaseToSnakeCase($property)] = $value;
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
