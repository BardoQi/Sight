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

namespace Bardoqi\Sight;

use Bardoqi\Sight\Abstracts\AbstractPresenter;
use Bardoqi\Sight\Enums\JoinTypeEnum;
use Bardoqi\Sight\Enums\MappingTypeEnum;
use Bardoqi\Sight\Enums\PaginateTypeEnum;
use Bardoqi\Sight\Enums\RelationEnum;
use Bardoqi\Sight\Exceptions\InvalidArgumentException;
use Bardoqi\Sight\Map\MultiMap;
use Bardoqi\Sight\Map\SingleMap;
use Bardoqi\Sight\Mapping\FieldMapping;
use Bardoqi\Sight\Relations\Relation;
use Bardoqi\Sight\Traits\PresenterTrait;

/**
 * Class Presenter.
 */
class Presenter extends AbstractPresenter
{
    use PresenterTrait;

    /**
     * @var string
     */
    public $error = '';

    /**
     * @var string
     */
    protected $message = '';

    /**
     * @var int
     */
    protected $status_code = 200;

    /**
     * Presenter Constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param array|string $field_list
     *
     * @return $this
     */
    public function selectFields($field_list)
    {
        if ((is_string($field_list)) &&
            (false !== strpos($field_list, ','))) {
            $field_list = explode(',', $field_list);
        }
        $this->field_list = $field_list;

        return $this;
    }

    /**
     * @param array|Collection $data_list
     * @param string           $alias
     * @param null|string      $data_path It is for the data from elasticsearch
     * @param null|string      $keyed_by
     *
     * @return $this
     */
    public function fromLocal($data_list, $alias = 'main', $data_path = null, $keyed_by = null)
    {
        if (null !== $data_path) { // maybe id is elasticsearch result
            $data_list = Arr::get($data_list, $data_path);
        }
        if (! is_array($data_list)) {
            throw InvalidArgumentException::ParamaterIsNotArray();
        }
        if (0 == count($data_list)) {
            throw InvalidArgumentException::LocalArrayCantBeEmpty();
        }
        $data_list = $this->huskPaginator($data_list);
        if (0 == count($data_list)) {
            throw InvalidArgumentException::LocalArrayCantBeEmpty();
        }
        $this->local_alias = $alias;
        $this->local_list = SingleMap::of($data_list,$keyed_by);

        return $this;
    }

    /**
     * @param        $data_item
     * @param string $alias
     * @param null   $data_path
     *
     * @return \Bardoqi\Sight\Presenter
     */
    public function fromLocalItem($data_item, $alias = 'main', $data_path = null)
    {
        return $this->fromLocal([$data_item], $alias, $data_path);
    }

    /**
     * pluck the values from given field
     * support the comma sepereted values.
     *
     * @param mixed ...$fields
     *
     * @return array
     */
    public function pluck(...$fields)
    {
        if (! is_array($fields)) {
            $fields = [$fields];
        }
        $out_array = [];
        foreach ($this->local_list as $item) {
            foreach ($fields as $key) {
                $out_array[] = $item[$key];
            }
        }

        if (! empty($out_array)) {
            /** maybe the value is comma separated values */
            try {
                $out_str = implode(',', $out_array);
                $out_array = explode(',', $out_str);
            } catch (\Exception $e) {
                dd($e->getMessage(), $out_array, $fields, $this->local_list);
            }

            return array_unique($out_array);
        }

        return [];
    }

    /**
     * Array join fonction for setting the relations.
     *
     * @param array|Collection $data_list
     * @param string           $alias
     * @param string           $keyed_by
     *
     * @return $this
     */
    public function innerJoinForeign($data_list, $alias, $keyed_by = 'id')
    {
        $this->addJoinList($data_list, $alias, $keyed_by, JoinTypeEnum::INNER_JOIN);

        return $this;
    }

    /**
     * Array join fonction for setting the relations.
     *
     * @param array|Collection $data_list
     * @param string           $alias
     * @param string           $keyed_by
     *
     * @return $this
     */
    public function outerJoinForeign($data_list, $alias, $keyed_by = 'id')
    {
        $this->addJoinList($data_list, $alias, $keyed_by, JoinTypeEnum::OUTER_JOIN);

        return $this;
    }

    /**
     * @param $local_field
     * @param $foreign_alias
     * @param $foreign_field
     * @param $relation_type
     *
     * @return $this
     */
    public function onRelation(
        $local_field,
        $foreign_alias,
        $foreign_field,
        $relation_type = RelationEnum::HAS_ONE
    ) {
        $this->addRelation(
            $local_field,
            $foreign_alias,
            $foreign_field,
            $relation_type
        );

        return $this;
    }

    /**
     * @param \Bardoqi\Sight\Relations\Relation $relation
     *
     * @return $this
     */
    public function onRelationbyObject(Relation $relation)
    {
        $this->relations->addRelationbyObject($relation);

        return $this;
    }

    /**
     * @param        $key
     * @param        $src
     * @param int    $type
     * @param string $alias
     *
     * @return $this
     */
    public function addFieldMapping($key, $src, $type = MappingTypeEnum::FIELD_NAME, $alias = '')
    {
        $this->field_mapping->addMapping($key, $src, $type, $alias);

        return $this;
    }

    /**
     * @param \Bardoqi\Sight\Mapping\FieldMapping $mapping
     *
     * @return $this
     */
    public function addFieldMappingByObject(FieldMapping $mapping)
    {
        /** @var \Bardoqi\Sight\Mapping\FieldMappingList */
        $this->field_mapping->addMappingByObject($mapping);

        return $this;
    }

    /**
     * @param $mapping_list
     * format of $mapping:
     *  [
     *       ['key1' => ['src'=>a, 'type'=>b， 'alias'=c  ]],
     *       ['key1' => ['src'=>a, 'type'=>b   'alias'=c  ]],
     *  ]
     *
     * @return $this
     */
    public function addFieldMappingList($mapping_list)
    {
        $this->mapping_list = $mapping_list;

        return $this;
    }

    /**
     * @param $name
     * @param $callback
     *
     * @return $this
     */
    public function addFormatter($name, $callback)
    {
        $this->data_formatter->addFunction($name, $callback);

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $out_array = [];
        foreach ($this->listItems() as  $item) {
            $out_array[] = $this->transform($item);
        }

        return $out_array;
    }

    /**
     * @return mixed
     */
    public function toItemArray()
    {
        $out_array = $this->toArray();

        return $out_array[0];
    }

    /**
     * @param int $paginate_type
     *
     * @return array
     */
    public function toPaginateArray($paginate_type = PaginateTypeEnum::PAGINATE_API)
    {
        $out_array = $this->toArray();
        $result = $this->getPaginData($paginate_type);
        $result['data'] = $out_array;

        return $result;
    }

    /**
     * @param $parent_id_key
     *
     * @return array
     */
    protected function toTreeArray($parent_id_key = 'parent_d')
    {
        $src_array = $this->toArray();
        $out_array = [];
        $ref_array = [];
        foreach ($src_array as $key => $item) {
            $parent_id = $item[$parent_id_key];
            $item['is_leaf'] = 1;
            $item['children'] = [];
            $id = $item['id'];

            if (0 == $parent_id) {
                $ref_array[$id] = &$out_array[];
                $ref_array[$id] = $item;
                continue;
            }
            $ref_array[$id] = &$ref_array[$parent_id]['children'][];
            $ref_array[$id] = $item;
            $ref_array[$parent_id]['is_leaf'] = 0;
        }

        return $out_array;
    }

    /**
     * @param $field_name
     *
     * @return mixed
     */
    public function getValue($field_name)
    {
        $item = $this->current_item;

        return $this->buildItem($field_name, $item);
    }

    /**
     * @return \Bardoqi\Sight\Iterators\CombineItem;
     */
    public function getCurrentItem()
    {
        return $this->current_item;
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param $error
     * @param int $code
     */
    public function setError($error, $code = 100)
    {
        $this->error = $error;
        $this->status_code = $code;
    }

    /**
     * @param $message
     * @param int $code
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @param string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param $code
     */
    public function setStatusCode($code)
    {
        $this->status_code = $code;
    }

    /**
     * @param $code
     */
    public function getStatusCode($code)
    {
        return $this->status_code;
    }


}
