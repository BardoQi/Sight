<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: bardo
 * Date: 2020-09-06
 * Time: 16:14.
 */

namespace Bardoqi\Sight\Iterators;

/**
 * Class ListIterator.
 */
final class ListIterator
{
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

    public function __construct()
    {
    }

    /**
     * @return \Bardoqi\Sight\Abstracts\static
     */
    public static function of()
    {
        return new static();
    }

    /**
     * @param $local_list
     * @param $join_lists
     * @param $relation_list
     *
     * @return $this
     */
    public function setList($local_list, $join_lists, $relation_list)
    {
        $this->local_list = $local_list;
        $this->join_lists = $join_lists;
        $this->relation_list = $relation_list;

        return $this;
    }

    /**
     * @return \Generator
     */
    public function LocalItems()
    {
        /** @var \Bardoqi\Sight\Map\MultiMap $local_list */
        $local_list = $this->local_list;
        if (0 === $local_list->count()) {
            yield [];

            return false;
        }
        foreach ($local_list->singleListItems() as $key => $item) {
            $new_item = CombineItem::getInstance();
            $new_item->addLocalItem($item);
            yield $new_item;
        }
    }

    /**
     * @param \Bardoqi\Sight\Iterators\CombineItem $item
     *
     * @return \Generator
     */
    protected function hasOneList()
    {
        /**
         * @var int                                  $key
         * @var \Bardoqi\Sight\Iterators\CombineItem $item
         */
        foreach ($this->LocalItems() as $key => $item) {
            /** @var \Bardoqi\Sight\Relations\RelationList $relation_list */
            $relation_list = $this->relation_list;
            foreach ($relation_list->hasOneRelations() as $alias => $relation) {
                $local_key = $item->getItemValue($relation->local_field);
                /** @var \Bardoqi\Sight\Map\MultiMap $join_list */
                $join_list = $this->join_lists[$alias];
                $list = $join_list->getHasOne($local_key);
                $item->addJoinItem($alias, $list);
            }
            foreach ($relation_list->hasManyMergeRelations() as $alias => $relation) {
                $local_key = $item->getItemValue($relation->local_field);
                /** @var \Bardoqi\Sight\Map\MultiMap $join_list */
                $join_list = $this->join_lists[$alias];
                $list = $join_list->getHasManyMerge($local_key);
                $item->addJoinItem($alias, $list);
            }
            foreach ($relation_list->hasManySplitRelations()as $alias => $relation) {
                $local_key = $item->getItemValue($relation->local_field);
                /** @var \Bardoqi\Sight\Map\MultiMap $join_list */
                $join_list = $this->join_lists[$alias];
                $list = $join_list->getHasManySplit($local_key);
                $item->addJoinItem($alias, $list);
            }
            yield $key => $item;
        }
    }

    /**
     * @param \Bardoqi\Sight\Iterators\CombineItem $item
     *
     * @return \Bardoqi\Sight\Iterators\ListIterator|\Bardoqi\Sight\Iterators\TreeIterator
     */
    protected function buildTreeIterator(CombineItem $item)
    {
        /**
         * @var \Bardoqi\Sight\Iterators\TreeIterator $tree_iterator
         * @var \Bardoqi\Sight\Iterators\TreeIterator $iterator_node
         */
        $tree_iterator = $iterator_node = null;
        /**
         * @var string                            $alias
         * @var \Bardoqi\Sight\Relations\Relation $relation
         */
        $relation_list = $this->relation_list;
        foreach ($relation_list->hasManyRelations() as $alias => $relation) {
            $local_key = $item->getItemValue($relation->local_field);

            /** @var \Bardoqi\Sight\Map\MultiMap $join_list */
            $join_list = $this->join_lists[$alias];
            $list = $join_list->getHasOne($local_key);
            if (null === $tree_iterator) {
                $iterator_node = $tree_iterator = TreeIterator::of($list, $alias);
            } else {
                $iterator_node = $iterator_node->addChildren($list, $alias);
            }
        }

        return $tree_iterator;
    }

    /**
     * @return \Generator|mixed
     */
    public function listItems()
    {
        foreach ($this->hasOneList() as $key => $item) {
            /** @var \Bardoqi\Sight\Iterators\TreeIterator $tree_iterator */
            $tree_iterator = $this->buildTreeIterator($item);
            if (null === $tree_iterator) {
                yield $item;
            } else {
                foreach ($tree_iterator->listItems($item) as $key=> $new_item) {
                    yield $new_item;
                }
            }
        }
    }
}
