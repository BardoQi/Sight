<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: bardo
 * Date: 2020-09-06
 * Time: 18:13
 */

namespace Bardoqi\Sight\Iterators;

/**
 * Class TreeIterator
 *
 * @package Bardoqi\Sight\Iterators
 */
final class TreeIterator
{
    /**
     * @var array;
     */
    protected $list;

    /**
     * @var string
     */
    protected $alias;

    /**
     * @var TreeIterator
     */
    public $children = null;

    /**
     *
     */
    public function __construct($list,$alias)
    {
        $this->list = $list;
        $this->alias = $alias;
    }

    /**
     * @param $is_inner
     *
     * @return \Bardoqi\Sight\Abstracts\AbstractIterator
     */
    public static function of($list,$alias){
        return new static($list,$alias);
    }

    /**
     * @param $list
     *
     * @return TreeIterator
     */
    public function addChildren($list,$alias){
        $this->children = TreeIterator($list,$alias);
        retrun & $this->children;
    }

    /**
     * @param \Bardoqi\Sight\Iterators\CombineItem $combine_item
     *
     * @return \Generator
     */
    public function listItems(CombineItem $combine_item){
        foreach($this->list as $key => $item){
            $combine_item->addJoinItem($this->alias,$item);
            if(null !== $this->children){
                yield $this->children->listItems($combine_item);
            }else{
                yield $combine_item;
            }
        }
    }

}
