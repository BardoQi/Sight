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

namespace Bardoqi\Sight\Iterators;

use Bardoqi\Sight\Exceptions\InvalidArgumentException;
use Bardoqi\Sight\Map\SingleMapItem;

/**
 * Class CombineItem.
 */
final class CombineItem
{
    /**
     * @var null
     */
    public static $instance = null;

    /**
     * @var array
     */
    public $local_item = [];

    /**
     * @var array
     */
    public $join_items = [];

    /**
     * @var array
     */
    public $alias_mapping = [];

    private function __construct()
    {
    }

    /**
     * @return \Bardoqi\Sight\Iterators\CombineItem
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new static();
        }

        return self::$instance->reNew();
    }

    /**
     * @return $this
     */
    public function reNew()
    {
        $this->local_item = [];
        $this->join_items = [];

        return $this;
    }

    /**
     * @param $item
     *
     * @return void
     */
    public function addLocalItem($item)
    {
        $this->local_item = $item;
    }

    /**
     * @param $alias
     * @param $item
     *
     * @return void
     */
    public function addJoinItem($alias, $item)
    {
        $this->join_items[$alias] = $item;
    }

    /**
     * @param      $column_name
     * @param int  $offset
     * @param null $alias
     *
     * @return mixed|string
     */
    public function getItemValue($column_name, $offset = 0, $alias = null)
    {
        if (empty($alias)) {
            /** @var \Bardoqi\Sight\Map\Interfaces\IMapItem $item */
            $item = $this->local_item;
            if ($item->hasColumn($column_name)) {
                return $item->getItemValue($column_name, $offset);
            }

            return '';
        }
        /** @var \Bardoqi\Sight\Map\Interfaces\IMapItem $item */
        $item = $this->join_items[$alias];
        if (null != $item) {
            if ($item->hasColumn($column_name)) {
                return $item->getItemValue($column_name, $offset);
            }
        }

        return '';
    }

    /**
     * @param $column_name
     *
     * @return array
     */
    public function getAliasMapping($column_name)
    {
        // if we have not got the alias, we find first
        if (! isset($this->alias_mapping[$column_name])) {
            //  if column is in the local_item, we use the default alias 'local'
            if (array_key_exists($column_name, $this->local_item)) {
                $this->alias_mapping[$column_name] = 'local';
            }
            // Then we find in the join_items
            /**
             * @var string $alias
             * @var \Bardoqi\Sight\Map\Interfaces\IMapItem $list
             */
            foreach ($this->join_items as $alias => $list) {
                if ($list->hasColumn($column_name)) {
                    $this->alias_mapping[$column_name] = $alias;
                    break;
                }
            }
        }

        return $this->alias_mapping[$column_name];
    }

    /**
     * @param     $alias
     * @param int $offfset
     *
     * @return \Bardoqi\Sight\Map\Interfaces\IMap
     */
    public function getMapItem($alias, $offfset = 0)
    {
        if (('local' == $alias) || (empty($alias))) {
            return $this->local_item;
        }

        if (array_key_exists($alias, $this->join_items)) {
            /** @var \Bardoqi\Sight\Map\Interfaces\IMapItem $join_item */
            $join_item = $this->join_items[$alias];
            if ($join_item instanceof SingleMapItem) {
                return $join_item;
            }
            if(null === $offset){
                return $join_item;
            }

            return SingleMapItem::of($join_item[$offfset], $join_item->keyed_by, $join_item->join_type);
        }

        return null;
    }

    /**
     * @param string  $path
     * @param null|string $alias
     *
     * @return mixed
     */
    public function findByPath($path, $alias = null)
    {
        // First we get the field name from path
        $path_arr = explode('.', $path);

        // we must get the alias when it is null.
        // we should get the alias by $field_name
        if (null === $alias) {
            $field_name = $path_arr[0];
            $alias = $this->getAliasMapping($field_name);
        }
        // get the item from data
        /** @var \Bardoqi\Sight\Map\Interfaces\IMapItem $map_item */
        $map_item = $this->getMapItem($alias);
        if (null === $map_item) {
            throw InvalidArgumentException::JsonFieldsNotFound($alias);
        }
        // call the findByPath of IMapItem
        return $map_item->findByPath($path_arr);
    }

    /**
     * @param $alias
     *
     * @return mixed
     */
    public function getData($alias = null)
    {
        // if alias is null, we get the local_item;
        if (null === $alias) {
            return $this->local_item;
        }
        // if there is no alias key in the join_items
        // we also get the local item.
        if (! array_key_exists($alias, $this->join_items)) {
            return $this->local_item;
        }

        return $this->join_items[$alias];
    }

    /**
     * @param $alias
     *
     * @return \Generator
     */
    public function hasManyOffsets($alias)
    {
        if (array_key_exists($alias, $this->join_items)) {
            $items = $this->join_items[$alias];
        }
//        else{
//            throw InvalidArgumentException::JoinItemNotFound($alias);
//        }
        /** @var \Bardoqi\Sight\Map\MultiMapItem $items */
        if (isset($items)) {
            foreach ($items->hasManyOffsets() as $offset) {
                yield $offset;
            }
        }
    }
}
