<?php

declare(strict_types=1);
/*
 * This file is part of the bardoqi/sight package.
 *
 * (c) BardoQi <bardoqi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bardoqi\Sight\Mapping;

use Bardoqi\Sight\Enums\MappingTypeEnum;
use Bardoqi\Sight\Exceptions\InvalidArgumentException;

/**
 * Class FieldMapping.
 */
final class FieldMapping
{
    /**
     * The mapping key shoud be same in the field list.
     *
     * @var string
     */
    public $key;

    /**
     * Where we could get the value.
     *
     * @var string
     */
    public $src;

    /**
     * Define the manaer of getting value.
     *
     * @var int
     */
    public $type;

    /**
     * @var string
     */
    public $alias;

    /**
     * FieldMapping Constructor.
     *
     * @param $key
     * @param $src
     * @param $type
     * @param $alias
     */
    public function __construct($key = '', $src = '', $type = MappingTypeEnum::FIELD_NAME, $alias = '')
    {
        $this->key = $key;
        $this->src = $src;
        $this->type = $type;
        $this->alias = $alias;
    }

    /**
     * @param $key
     * @param $src
     * @param $type
     * @param $alias
     *
     * @return \Bardoqi\Sight\Mapping\FieldMapping
     */
    public static function of($key = '', $src = '', $type = MappingTypeEnum::FIELD_NAME, $alias = '')
    {
        return new static($key,$src,$type,$alias);
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        if (empty($this->key)) {
            throw InvalidArgumentException::MappingKeyCanNotBeEmpty();
        }
        if (empty($this->src)) {
            throw InvalidArgumentException::MappingSourceCanNotBeEmpty();
        }
        if (!MappingTypeEnum::valid($this->type)) {
            throw InvalidArgumentException::MappingTypeIsNotValid();
        }

        return true;
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    public function key($key = null)
    {
        if (null == $key) {
            return $this->key;
        }
        $this->key = $key;

        return $this;
    }

    /**
     * @param $src
     *
     * @return mixed
     */
    public function src($src = null)
    {
        if (null == $src) {
            return $this->src;
        }
        $this->src = $src;

        return $this;
    }

    /**
     * @param $type
     *
     * @return mixed
     */
    public function type($type = null)
    {
        if (null == $type) {
            return $this->type;
        }
        $this->type = $type;

        return $this;
    }

    /**
     * @param $alias
     *
     * @return $this|string
     */
    public function alias($alias = null)
    {
        if (null == $alias) {
            return $this->alias;
        }
        $this->alias = $alias;

        return $this;
    }

    /**
     * @param $key
     * @param $array_item
     *
     * @return \Bardoqi\Sight\Mapping\FieldMapping
     */
    public static function fromArray($key, $array_item)
    {
        try {
            $alias = '';
            /** item format is  ['key' => ['src'=>a, 'type'=>b  ]] */
            if (isset($array_item['src'])) {
                if (isset($array_item['alias'])) {
                    ['src'=> $src, 'type'=>$type, 'alias'=>$alias] = $array_item;
                } else {
                    ['src'=> $src, 'type'=>$type] = $array_item;
                }
            } else {
                /** item format is  ['key' => [a, b]] */
                if (isset($array_item['alias'])) {
                    list($src, $type, $alias) = $array_item;
                } else {
                    list($src, $type) = $array_item;
                }
            }
        } catch (\Exception $e) {
            throw new \InvalidArgumentException($e->getMessage());
        }

        return self::of($key, $src, $type, $alias);
    }
}
