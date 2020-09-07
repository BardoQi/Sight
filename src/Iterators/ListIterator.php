<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: bardo
 * Date: 2020-09-06
 * Time: 16:14
 */

namespace Bardoqi\Sight\Iterators;

/**
 * Class ListIterator
 *
 * @package Bardoqi\Sight\Iterators
 */
final class ListIterator
{
    /**
     * @var bool
     */
    protected $is_inner = true;

    /**
     * @var array
     */
    protected $local_list = [];

    /**
     * @var array
     */
    protected $join_lists = [];

    /**
     * @var array
     */
    protected $relation_list = [];


    const JOIN_ONE = 0;


    const JOIN_MANY = 1;

    /**
     *
     */
    public function __construct()
    {

    }

    /**
     * @param $is_inner
     *
     * @return \Bardoqi\Sight\Abstracts\AbstractIterator
     */
    public static function of(){
        return new static();
    }

    /**
     * @param $local_list
     * @param $join_lists
     * @param $relation_list
     *
     * @return $this
     */
    public function setList($local_list,$join_lists,$relation_list){
        $this->local_list = $local_list;
        $this->join_lists = $join_lists;
        $this->relation_list = $relation_list;
        return $this;
    }

    /**
     * @return \Generator
     */
    protected function hasManyList(){
        foreach ($this->relation_list as $alias => $relation){
            if($relation->join_type != self::JOIN_MANY){
                continue;
            }
            $local_key = $relation->local_field;
            $list = $this->join_lists[$alias][$local_key];
            yield $alias => $list;
        }
    }

    /**
     * @return Bardoqi\Sight\Iterators\TreeIterator
     */
    protected function hasOneList(){
        /** @var \Bardoqi\Sight\Iterators\TreeIterator $tree_iterator */
        $tree_iterator = null;
        /** @var \Bardoqi\Sight\Iterators\TreeIterator $iterator_node */
        $iterator_node = null;
        foreach ($this->relation_list as $alias => $relation){
            if($relation->join_type != self::JOIN_ONE){
                continue;
            }
            $local_key = $relation->local_field;
            $list = $this->join_lists[$alias][$local_key];
            if(null === $tree_iterator){
                $iterator_node = $tree_iterator = TreeIterator::of($list,$alias);
            }else{
                $iterator_node = $iterator_node->addChildren($list,$alias);
            }
            return $tree_iterator;
        }
    }

    /**
     * @return \Generator|mixed
     */
    public function listItems()
    {
        foreach ($this->local_list as $key => $item){
            $new_item = CombineItem::getInstance();
            $new_item->addLocalItem($item);
            foreach($this->hasManyList() as $alias => $list){
                $new_item->resetJoinItems();
                $new_item->addJoinItemList($alias,$list);
            }
            /** @var \Bardoqi\Sight\Iterators\TreeIterator $tree_iterator */
            $tree_iterator = $this->hasOneList();
            yield $key => $tree_iterator->listItems($new_item);
        }
    }
}
