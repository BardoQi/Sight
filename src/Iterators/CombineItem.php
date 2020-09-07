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
        $this->join_items[$alias][] = $item;
    }

    /**
     * @param $alias
     * @param $item
     *
     * @return void
     */
    public function setJoinItem($alias,$item){
        $this->join_items[$alias] = [];
        $this->join_items[$alias][] = $item;
    }


    /**
     * @param $alias
     * @param $list
     *
     * @return void
     */
    public function addJoinItemList($alias,$list){
        $this->join_items[$alias][] = $list;
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
        if(null === $alias){
            if(isset($this->local_item[$item_key])){
                return $this->local_item[$item_key];
            }
            return '';
        }
        if(isset($this->join_items[$alias])){
            $item = $this->join_items[$alias];
            if(isset($item[$offset])){
                $sub_item = $item[$offset];
                if(isset($sub_item[$item_key])){
                    return $sub_item[$item_key];
                }
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
        if(!isset($this->alias_mapping[$item_key])){

        }
        return $this->alias_mapping[$item_key];
    }

    /**
     * @param      $item_key
     * @param int  $offset
     * @param int  $path
     * @param null $alias
     *
     * @return void
     */
    public function findByPath($item_key,$path,$offset = 0, $alias = null){
        if(null ===  $alias){
            $this->getAliasMapping($item_key);
        }
    }

}
