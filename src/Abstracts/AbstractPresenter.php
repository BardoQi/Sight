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

namespace Bardoqi\Sight\Abstracts;

use Bardoqi\Sight\Enums\JoinTypeEnum;
use Bardoqi\Sight\Enums\MappingTypeEnum;
use Bardoqi\Sight\Enums\PaginateTypeEnum;
use Bardoqi\Sight\Enums\RelationEnum;
use Bardoqi\Sight\Exceptions\InvalidArgumentException;
use Bardoqi\Sight\Formatters\DataFormatter;
use Bardoqi\Sight\Iterators\ListIterator;
use Bardoqi\Sight\Map\MultiMap;
use Bardoqi\Sight\Mapping\FieldMapping;
use Bardoqi\Sight\Mapping\FieldMappingList;
use Bardoqi\Sight\Mapping\FieldMappingValidator;
use Bardoqi\Sight\Registries\FunctionRegistry;
use Bardoqi\Sight\Relations\Relation;
use Bardoqi\Sight\Relations\RelationList;
use Closure;
use Illuminate\Support\Arr;

/**
 * Class AbstractPresenter.
 */
abstract class AbstractPresenter
{
    /**
     * Keep the join relations.
     *
     * @var \Bardoqi\Sight\Relations\RelationList
     */
    protected $relations = null;

    /**
     * Which fields shoud be output.
     *
     * @var array
     */
    protected $field_list = [];

    /**
     * The alias of the local array.
     *
     * @var string
     */
    protected $local_alias = '';

    /**
     * The local array.
     *
     * @var null|\Bardoqi\Sight\Map\SingleMap
     */
    protected $local_list = null;

    /**
     * The foreign arrays for join.
     *
     * @var array
     */
    protected $join_lists = [];

    /**
     * Keep the current item.
     *
     * @var null|\Bardoqi\Sight\Iterators\CombineItem
     */
    protected $current_item = null;

    /**
     * Keep the fields transform mappings.
     *
     * @var \Bardoqi\Sight\Mapping\FieldMappingList
     */
    protected $field_mapping = null;

    /**
     * Keeping the paginata data.
     *
     * @var array
     */
    protected $paginate_data = [];

    /**
     * Keep the method added on fly.
     *
     * @var array
     */
    protected $macros = [];

    /**
     * object of DataFormatter.
     *
     * @var null | DataFormatter
     */
    public $data_formatter = null;

    /**
     * Keep the mapping array.
     *
     * @var array
     */
    protected $mapping_list = [];

    /**
     * AbstractPresenter Constructor.
     */
    public function __construct()
    {
        $this->field_mapping = FieldMappingList::of();
        $this->data_formatter = DataFormatter::getInstance();
        $this->relations = RelationList::of();
        if (true === config('app.debug')) {
            FunctionRegistry::getInstance()->setItem('hasMethod', $this, 'hasMethod');
        }
    }

    /**
     * short static function to create new instance.
     *
     * @return \Bardoqi\Sight\Abstracts\static
     */
    public static function of()
    {
        return new static();
    }

    /**
     * @param $method
     * @param $parameters
     *
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        $instance = app(get_called_class());

        return $instance->$method(...$parameters);
    }

    /**
     * Handle dynamic method calls.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (isset($this->macros[$method])) {
            return call_user_func_array($this->macros[$method], $parameters);
        }
        if (method_exists($this, $method)) {
            return $this->$method(...$parameters);
        }

        throw InvalidArgumentException::MethodNotFound($method);
    }

    /**
     * Add the method on fly.
     *
     * @param $function_name
     * @param $methodCallable
     *
     * @return $this
     */
    public function addFunction($function_name, $methodCallable)
    {
        if (! is_callable($methodCallable)) {
            throw InvalidArgumentException::FunctionMustBeCallable($function_name);
        }
        $this->macros[$function_name] = Closure::bind($methodCallable, $this, get_class());

        return $this;
    }

    /**
     * alias of the function addFunction.
     *
     * @param $method_name
     * @param $callable
     *
     * @return $this
     */
    public function addMethod($method_name, $callable)
    {
        return $this->addFunction($method_name, $callable);
    }

    /**
     * @param $method
     *
     * @return bool
     */
    public function hasMethod($method)
    {
        if (isset($this->macros[$method])) {
            return true;
        }
        if (method_exists($this, $method)) {
            return true;
        }

        return false;
    }

    /**
     * Peel the paginate data, retuern the pure data.
     *
     * @param $data_list
     * @param $save
     *
     * @return array
     */
    protected function huskPaginator($data_list, $save = true)
    {
        if ((isset($data_list['current_page']))
            && isset($data_list['data'])) {
            if ($save) {
                $this->paginate_data = Arr::except($data_list, 'data');
            }

            return $data_list['data'];
        }

        return $data_list;
    }

    /**
     * Add the array will to join.
     *
     * @param $data_list
     * @param $alias
     * @param $keyed_by
     * @param $join_type
     *
     * @return $this
     */
    protected function addJoinList($data_list, $alias, $keyed_by = null, $join_type = JoinTypeEnum::INNER_JOIN)
    {
        if (empty($keyed_by)) {
            throw InvalidArgumentException::KeyedByCannotBeEmpty();
        }
        /** Husk the data of paginator */
        $data_list = $this->huskPaginator($data_list, false);
        if (0 < count($data_list)) {
            if (! isset($data_list[0][$keyed_by])) {
                throw InvalidArgumentException::KeyedByIsNotCorrect($keyed_by);
            }
        }
        $this->join_lists[$alias] = MultiMap::of($data_list, $keyed_by, $join_type);

        return $this;
    }

    /**
     * Add the join relations.
     *
     * @param $local_field
     * @param $foreign_alias
     * @param $foreign_field
     * @param $reltion_type
     *
     * @return $this
     */
    protected function addRelation(
        $local_field,
        $foreign_alias,
        $foreign_field = 'id',
        $reltion_type = RelationEnum::HAS_ONE
    ) {
        if (! isset($this->join_lists[$foreign_alias])) {
            throw InvalidArgumentException::ForeignAliasNotExists();
        }

        $this->relations->addRelationByObject(Relation::of(
            $local_alias = $this->local_alias,
            $local_field,
            $foreign_alias,
            $foreign_field,
            $reltion_type
        ));

        return $this;
    }

    /**
     * For the performance, we'd better build the all mappping first.
     *
     * @return FieldMappingList
     */
    protected function initMapping()
    {
        $mapping = $this->mapping_list;
        foreach ($mapping as $key => $mapping_item) {
            try {
                $mapping_object = FieldMapping::fromArray($key, $mapping_item);
            } catch (\Exception $e) {
                throw InvalidArgumentException::MappingArrayIsNotValid($e->getMessage());
            }
            $this->field_mapping->addItem($mapping_object);
        }

        return $this->field_mapping;
    }

    /**
     * @return void
     */
    protected function prepareMapping()
    {
        $mapping = $this->initMapping();

        foreach ($this->field_list as $field_name) {
            if (isset($mapping[$field_name])) {
                continue;
            }
            if ($this->local_list->hasColumn($field_name)) {
                $mapping_item = FieldMapping::of()
                    ->key($field_name)
                    ->src($field_name)
                    ->type(MappingTypeEnum::FIELD_NAME);
                $mapping[$field_name] = $mapping_item;
                continue;
            }
            $hasMapping = false;
            foreach ($this->join_lists as $alias => $list) {
                if ($list->hasColumn($field_name)) {
                    $mapping_item = FieldMapping::of()
                        ->key($field_name)
                        ->src($field_name)
                        ->type(MappingTypeEnum::FIELD_NAME)
                        ->alias($alias);
                    $mapping[$field_name] = $mapping_item;
                    $hasMapping = true;
                }
            }
            if ($hasMapping) {
                continue;
            }
            throw InvalidArgumentException::FieldOrMappingNotFound($field_name);
        }
        // This is just for debuging. It wiil not rum in rpoduction env.
        if (true === config('app.debug')) {
            $mapping_validator = FieldMappingValidator::of(
                $this->local_list,
                $this->join_lists
            );
            $mapping_validator->validate($mapping);
        }
        $this->field_mapping = $mapping;
    }

    /**
     * Compose the items with the arrays.
     *
     * @return \Generator
     */
    protected function listItems()
    {
        $this->prepareMapping();
        /** @var ListIterator $list_iterator */
        $list_iterator = ListIterator::of()
            ->setList(
                $this->local_list,
                $this->join_lists,
                $this->relations
            );

        /**
         * @var int                                  $offset
         * @var \Bardoqi\Sight\Iterators\CombineItem $item
         */
        foreach ($list_iterator->listItems() as $offset => $item) {
            $this->current_item = $item;
            yield $offset => $item;
        }
    }

    /**
     * @param FieldMapping                         $mapping
     * @param \Bardoqi\Sight\Iterators\CombineItem $item
     *
     * @return mixed
     */
    protected function forwardCall($mapping, $item)
    {
        $method = $mapping->key();
        $value = $item->getItemValue($mapping->src(), 0, $mapping->alias());
        if (isset($this->macros[$method])) {
            return call_user_func_array($this->macros[$method], $value);
        }
        if (method_exists($this, $method)) {
            return $this->$method($value);
        }

        return false;
    }

    /**
     * @param \Bardoqi\Sight\Mapping\FieldMapping  $mapping
     * @param \Bardoqi\Sight\Iterators\CombineItem $item
     *
     * @return mixed
     */
    public function getItemValueWithMapping($mapping, $item)
    {
        switch ($mapping->type) {
            case MappingTypeEnum::FIELD_NAME:
                return $item->getItemValue($mapping->src(), 0, $mapping->alias());
            case MappingTypeEnum::DATA_FORMATER:
                $Formatter = $this->data_formatter;
                $value = $item->getItemValue($mapping->key(), 0, $mapping->alias());

                return call_user_func_array([$Formatter, 'format'], [$mapping->src(), $value]);
            case MappingTypeEnum::METHOD_NAME:
                return $this->forwardCall($mapping, $item);
            case MappingTypeEnum::ARRAY_PATH:
                return $item->findByPath($mapping->src(), $mapping->alias());
            case MappingTypeEnum::JOIN_FIELD:
                return $item->getItemValue($mapping->src(), 0, $mapping->alias());
            default:
                return $item->getItemValue($mapping->src(), 0, $mapping->alias());
        }
    }

    /**
     * Transform the field value.
     *
     * @param $field_name
     * @param \Bardoqi\Sight\Iterators\CombineItem $item
     *
     * @return mixed
     */
    protected function buildItem($field_name, $item)
    {
        if (isset($this->field_mapping[$field_name])) {
            /** @var \Bardoqi\Sight\Mapping\FieldMapping $mapping */
            $mapping = $this->field_mapping[$field_name];
            $newValue = $this->getItemValueWithMapping($mapping, $item);

            return $newValue;
        }
        // If  we have not got the value, we use the key
        return $item->getItemValue($field_name);
    }

    /**
     * build the item with evary fields.
     *
     * @param \Bardoqi\Sight\Iterators\CombineItem $item
     *
     * @return array
     */
    protected function transform($item)
    {
        $new_item = [];
        foreach ($this->field_list as $key) {
            $new_item[$key] = $this->buildItem($key, $item);
        }

        return $new_item;
    }

    /**
     * get the paginate data if neeed.
     *
     * @param $paginate_type
     *
     * @return array
     */
    protected function getPaginData($paginate_type)
    {
        if (empty($this->paginate_data)) {
            throw InvalidArgumentException::PaginateDataNotFound();
        }
        if (PaginateTypeEnum::PAGINATE_API == $paginate_type) {
            $paginate_data = Arr::only($this->paginate_data, ['current_page', 'from', 'last_page', 'per_page', 'to', 'total']);

            return $paginate_data;
        }

        return $this->paginate_data;
    }
}
