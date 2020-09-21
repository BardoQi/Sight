<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: bardo
 * Date: 2020-09-21
 * Time: 19:05
 */

namespace Bardoqi\Sight\Map;

use Bardoqi\Sight\Abstracts\AbstractList;
use Bardoqi\Sight\Enums\JoinTypeEnum;
use Bardoqi\Sight\Enums\RelationEnum;
use Bardoqi\Sight\Map\Interfaces\IMap;
/**
 * Class SingleMap
 *
 * @package Bardoqi\Sight\Map
 */
final class SingleMap extends AbstractList implements IMap
{
    /**
     * $keyed_by : Indicating which key is used for sort;.
     *
     * @var string
     */
    public $keyed_by = '';

    /**
     * $join_type : Keep the join type.
     *
     * @var int
     */
    public $join_type = 0;

    /**
     * @var array
     */
    private $empty_item = null;

    /**
     * Create a instance.
     *
     * @param null|array  $data
     * @param null|string $keyed_by
     *
     * @return static
     * @static
     */
    public static function of($data = null, $keyed_by = null, $join_type = JoinTypeEnum::INNER_JOIN)
    {
        $instance = new static();
        $instance->join_type = $join_type;
        $instance->keyed_by = $keyed_by;
        if (null !== $keyed_by) {
            foreach ($data as $item) {
                $instance->data[$item[$keyed_by]] = $item;
            }

            return $instance;
        }
        $instance->data = $data;

        return $instance;
    }

    /**
     * @return \Generator
     */
    public function getLocalOne()
    {
        foreach ($this->data as $key => $item) {
            yield $key => SingleMapItem::of($item, $this->keyed_by, 0);
        }
    }

    /**
     * @param $offset
     *
     * @return bool|\Generator
     */
    public function getItems($offset)
    {
        if (! isset($this->data[$offset])) {
            return [];
        }
        foreach ($this->data[$offset] as $key => $item) {
            yield $key => SingleMapItem::of($item, $this->keyed_by, 0);
        }
    }

    /**
     * @param $filter_list
     * @param \Bardoqi\Sight\Filters\Condition $filter_condition
     *
     * @return \Generator
     */
    public function listItems($filter_list,$filter_condition)
    {
        /**
         * for filter
         */
        if(!empty($filter_list)){
            foreach($filter_list as $filter_item){

                $key = $filter_item[$filter_condition->filter_field];
                $item=  $this->data[$key];
                yield $key => SingleMapItem::of($item, $this->keyed_by, 0);
            }
            return false;
        }
        foreach ($this->data as $key => $item) {
            yield $key => SingleMapItem::of($item, $this->keyed_by, 0);
        }
    }


    /**
     * @param $filter_list
     * @param \Bardoqi\Sight\Filters\Condition $filter_condition
     *
     * @return \Generator
     */
    public function singleListItems($filter_list,$filter_condition)
    {
        /**
         * for filter
         */
        if(!empty($filter_list)){
            foreach($filter_list[$filter_condition->filter_alias] as $filter_item){
                $key = $filter_item[$filter_condition->filter_field];
                $item=  $this->data[$key];
                yield $key => SingleMapItem::of($item, $this->keyed_by, 0);
            }
            return false;
        }
        foreach ($this->data as $key => $item) {
            yield $key => SingleMapItem::of($item, $this->keyed_by, 0);
        }
    }

    /**
     * @param $name
     *
     * @return bool
     */
    public function hasColumn($name)
    {
        // get the first item.
        $item = reset($this->data);
        return isset($item[$name]);
    }
}
