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
use Bardoqi\Sight\Formatters\DataFormatter;
use Bardoqi\Sight\Map\MultiMap;
use Bardoqi\Sight\Map\SingleMap;
use Bardoqi\Sight\Registries\FunctionRegistry;

/**
 * Class FieldMappingValidator.
 *
 * @desc This file only run in development with app.debug = true! It just is a debug tool.
 */
final class FieldMappingValidator
{
    /**
     * Keep the method names.
     *
     * @var array
     */
    protected $validators = [
        MappingTypeEnum::FIELD_NAME => 'validateFieldName',
        MappingTypeEnum::DATA_FORMATER => 'validateDataFormatter',
        MappingTypeEnum::METHOD_NAME => 'validMethodName',
        MappingTypeEnum::ARRAY_PATH => 'validateArrayPath',
        MappingTypeEnum::JOIN_FIELD => 'validateJoinField',
    ];

    /**
     * The local array.
     *
     * @var null|MultiMap
     */
    protected $local_list = null;

    /**
     * The foreign arrays for join.
     *
     * @var array
     */
    protected $join_lists = [];

    /**
     * object of DataFormatter.
     *
     * @var null | DataFormatter
     */
    public $data_formatter = null;

    /**
     * @param \Bardoqi\Sight\Map\SingleMap $local_list
     * @param array                       $join_lists
     *
     * AbstractPresenter Constructor
     */
    public function __construct(
        SingleMap $local_list,
        array $join_lists
    ) {
        $this->local_list = $local_list;
        $this->join_lists = $join_lists;
        $this->data_formatter = DataFormatter::getInstance();
    }

    /**
     * @param \Bardoqi\Sight\Mapping\FieldMappingList $field_mapping
     * @param \Bardoqi\Sight\Map\MultiMap             $local_list
     * @param array                                   $join_lists
     *
     * @return \Bardoqi\Sight\Mapping\FieldMappingValidator
     */
    public static function of(
        SingleMap $local_list,
        array $join_lists
    ) {
        return new static($local_list,$join_lists);
    }

    /**
     * @param $name
     *
     * @return mixed
     */
    private function hasMethod($name)
    {
        return FunctionRegistry::getInstance()
            ->forwardCall('hasMethod', $name);
    }

    /**
     * @return bool
     */
    public function validate($mapping_list)
    {
        foreach ($mapping_list as $item) {
            if (false === $this->validateItem($item)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param \Bardoqi\Sight\Mapping\FieldMapping $mapping
     *
     * @return mixed
     */
    private function validateItem($mapping)
    {
        $method = $this->validators[$mapping->type()];

        return $this->$method($mapping);
    }

    /**
     * @param $name
     * @param $alias
     *
     * @return mixed
     */
    private function hasField($name, $alias)
    {
        if ($this->local_list->hasColumn($name)) {
            return true;
        }
        if (! empty($alias)) {
            if (isset($this->join_lists[$alias])) {
                /** @var MultiMap $list */
                $list = $this->join_lists[$alias];

                return $list->hasColumn($name);
            }
        }
        /** @var MultiMap $list */
        foreach ($this->join_lists as $list) {
            if ($list->hasColumn($name)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $path
     * @param $alias
     *
     * @return mixed
     */
    private function hasPath($path, $alias)
    {
        $path_arr = explode('.', $path);
        $name = array_shift($path_arr);

        return $this->hasField($name, $alias);
    }

    /**
     * @param \Bardoqi\Sight\Mapping\FieldMapping $mapping
     *
     * @return bool
     */
    protected function validateFieldName($mapping)
    {
        return true;
    }

    /**
     * @param \Bardoqi\Sight\Mapping\FieldMapping $mapping
     *
     * @return bool
     */
    protected function validateDataFormatter($mapping)
    {
        if (! $this->data_formatter->hasMothod($mapping->src())) {
            throw InvalidArgumentException::FieldMappingIsInvalid($mapping->key());
        }

        return true;
    }

    /**
     * @param \Bardoqi\Sight\Mapping\FieldMapping $mapping
     *
     * @return bool
     */
    protected function validMethodName($mapping)
    {
        if (! $this->hasMethod($mapping->key())) {
            throw InvalidArgumentException::FieldMappingIsInvalid($mapping->key());
        }

        return true;
    }

    /**
     * @param \Bardoqi\Sight\Mapping\FieldMapping $mapping
     *
     * @return bool
     */
    protected function validateArrayPath($mapping)
    {
        if (! $this->hasPath($mapping->src(), $mapping->alias())) {
            throw InvalidArgumentException::FieldMappingIsInvalid($mapping->key());
        }

        return true;
    }

    /**
     * @param \Bardoqi\Sight\Mapping\FieldMapping $mapping
     *
     * @return bool
     */
    protected function validateJoinField($mapping)
    {
        if (! $this->hasField($mapping->src(), $mapping->alias())) {
            throw InvalidArgumentException::FieldMappingIsInvalid($mapping->key());
        }

        return true;
    }
}
