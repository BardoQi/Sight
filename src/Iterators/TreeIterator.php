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
 * Class TreeIterator.
 */
final class TreeIterator
{
    /**
     * @var \Bardoqi\Sight\Map\SingleMap;
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

    public function __construct($list, $alias)
    {
        $this->list = $list;
        $this->alias = $alias;
    }

    /**
     * @param $is_inner
     *
     * @return \Bardoqi\Sight\Abstracts\static
     */
    public static function of($list, $alias)
    {
        return new static($list,$alias);
    }

    /**
     * @param $list
     *
     * @return TreeIterator
     */
    public function addChildren($list, $alias)
    {
        $this->children = TreeIterator::of($list, $alias);

        return $this->children;
    }

    /**
     * @param \Bardoqi\Sight\Iterators\CombineItem $combine_item
     *
     * @return \Generator
     */
    private function items(CombineItem $combine_item)
    {
        if (0 === count($this->list)) {
            yield $combine_item;
        } else {
            foreach ($this->list->listItems() as $key => $item) {
                $combine_item->addJoinItem($this->alias, $item);
                yield $combine_item;
            }
        }
    }

    /**
     * @param \Bardoqi\Sight\Iterators\CombineItem $combine_item
     *
     * @return \Generator
     */
    private function childItems(CombineItem $combine_item)
    {
        if (null === $this->children) {
            yield $combine_item;
        } else {
            $node = $this->children;
            foreach ($node->listItems($combine_item) as $key => $items) {
                yield $items;
            }
        }
    }

    /**
     * @param \Bardoqi\Sight\Iterators\CombineItem $combine_item
     *
     * @return \Generator
     */
    public function listItems(CombineItem $combine_item)
    {  // TODO FIX YIELD
        foreach ($this->items($combine_item) as  $item) {
            foreach ($this->childItems($item) as  $node_item) {
                yield $node_item;
            }
        }
    }
}
