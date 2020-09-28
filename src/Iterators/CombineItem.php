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
     * @return void
     */
    private function __clone()
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
     * @param $alias
     * @param $item
     *
     * @return void
     */
    public function setJoinItem($alias, $item)
    {
        $this->join_items[$alias] = $item;
    }

    /**
     * @param $alias
     * @param $list
     *
     * @return void
     */
    public function addJoinItemList($alias, $list)
    {
        $this->join_items[$alias] = $list;
    }

    /**
     * @return bool
     */
    public function resetJoinItems()
    {
        $this->join_items = [];

        return true;
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
     * @param $item_key
     *
     * @return array
     */
    public function getAliasMapping($item_key)
    {

        if (! isset($this->alias_mapping[$item_key])) {
            if (isset($this->local_item[$item_key])) {
                $this->alias_mapping[$item_key] = 'local';
            }
            foreach ($this->join_items as $alias => $list) {
                if (isset($list[$item_key])) {
                    $this->alias_mapping[$item_key] = $alias;
                    break;
                }
            }
        }

        return $this->alias_mapping[$item_key];
    }

    /**
     * @param     $alias
     * @param int $offfset
     *
     * @return array|mixed
     */
    private function getMapItem($alias, $offfset = 0)
    {
        if (('local' == $alias) || (Empty($alias))) {
            return $this->local_item;
        }

        return $this->join_items[$alias][$offfset];
    }

    /**
     * @param int  $path
     * @param null $alias
     *
     * @return mixed
     */
    public function findByPath($path, $alias = null)
    {
        $path_arr = explode('.', $path);
        $item_key = $path_arr[0];

        if (null === $alias) {
            $alias = $this->getAliasMapping($item_key);
        }

        /** @var \Bardoqi\Sight\Map\MultiMapItem $map_item */
        $map_item = $this->getMapItem($alias);

        return $map_item->findByPath($path_arr);
    }

    /**
     * @param $alias
     *
     * @return mixed
     */
    public function getData($alias = null)
    {
        if (null === $alias) {
            return $this->local_item;
        }
        if (! isset($this->join_items[$alias])) {
            return $this->local_item;
        }

        return $this->join_items[$alias];
    }
}
