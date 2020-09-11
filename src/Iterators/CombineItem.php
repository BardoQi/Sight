<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: bardo
 * Date: 2020-09-06
 * Time: 14:55
 */

namespace Bardoqi\Sight\Iterators;

/**
 * Class CombineItem
 *
 * @package Bardoqi\Sight\Iterators
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

    /**
     *
     */
    private function __construct()
    {

    }

    /**
     * @return void
     */
    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    /**
     * @return \Bardoqi\Sight\Iterators\CombineItem
     */
    public static function getInstance(){
        if(null === self::$instance){
            self::$instance = new static();
        }
        return self::$instance->reNew();
    }

    /**
     * @return $this
     */
    public function reNew(){
        $this->local_item = [];
        $this->join_items = [];
        return $this;
    }

    /**
     * @param $item
     *
     * @return void
     */
    public function addLocalItem($item){
        $this->local_item = $item;
    }

    /**
     * @param $alias
     * @param $item
     *
     * @return void
     */
    public function addJoinItem($alias,$item){
        $this->join_items[$alias] = $item;
    }

    /**
     * @param $alias
     * @param $item
     *
     * @return void
     */
    public function setJoinItem($alias,$item){
        $this->join_items[$alias] = $item;
    }


    /**
     * @param $alias
     * @param $list
     *
     * @return void
     */
    public function addJoinItemList($alias,$list){
        $this->join_items[$alias] = $list;
    }


    /**
     * @return bool
     */
    public function resetJoinItems(){
        $this->join_items = [];
        return true;
    }

    /**
     * @param      $item_key
     * @param int  $offset
     * @param null $alias
     *
     * @return mixed|string
     */
    public function getItemValue($item_key,$offset = 0, $alias = null){
        if(empty($alias)){
            /** @var \Bardoqi\Sight\Map\SingleMapItem $item */
            $item = $this->local_item;
            if($item->hasKey($item_key)){
                return $item->getItemValue($item_key);
            }
            return '';
        }
        /** @var \Bardoqi\Sight\Map\Interfaces\IMapItem $item */

        $item = $this->join_items[$alias];
        if($item->hasKey($item_key)){
            return $item->getItemValue($item_key);
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
        if(!isset($this->alias_mapping[$item_key])){
            if(isset($this->local_item[$item_key])){
                $this->alias_mapping[$item_key] = 'local';
            }
            foreach ($this->join_items as $alias => $list){
                if(isset($list[$item_key])){
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
    private function getMapItem($alias,$offfset = 0){
        if ('local' == $alias){
            return $this->local_item;
        }else{
            return $this->join_items[$alias][$offfset];
        }
    }

    /**
     * @param int  $path
     * @param null $alias
     *
     * @return void
     */
    public function findByPath($path, $alias = null){
        $path_arr = explode(',',$path);
        $item_key = $path_arr[0];
        if(null ===  $alias){
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
    public function getData($alias){
        return $this->join_items[$alias];
    }

}
