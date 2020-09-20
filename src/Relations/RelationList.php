<?php

/*
 * This file is part of the bardoqi/sight package.
 *
 * (c) BardoQi <bardoqi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Bardoqi\Sight\Relations;

use Bardoqi\Sight\Abstracts\AbstractList;
use Bardoqi\Sight\Enums\RelationEnum;

/**
 * Class RelationList.
 */
final class RelationList extends AbstractList
{
    /**
     * FieldMapping Constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return \Bardoqi\Sight\Mapping\static
     */
    public static function of()
    {
        return new static();
    }

    /**
     * @param $local_alias
     * @param $local_field
     * @param $foreign_alias
     * @param $foreign_field
     * @param $relation_type
     *
     * @return $this
     */
    public function addRelation(
        $local_alias,
        $local_field,
        $foreign_alias,
        $foreign_field,
        $relation_type
    ) {
        $this->data[] = Relation::of(
            $local_alias,
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
    public function addRelationByObject(Relation $relation)
    {
        if ($relation->isValid()) {
            $this->data[$relation->foreignAlias()] = $relation;
        }

        return $this;
    }

    /**
     * @param mixed ...$param
     *
     * @return $this
     */
    public function addItem(...$param)
    {
        if ($param[0] instanceof Relation) {
            return $this->addRelationByObject($param[0]);
        }

        return $this->addRelation(...$param);
    }

    /**
     * @return \Generator
     */
    public function hasOneRelations()
    {
        /** @var \Bardoqi\Sight\Relations\Relation $relation */
        foreach ($this->data as $alias => $relation) {
            if (RelationEnum::HAS_ONE == $relation->relation_type) {
                yield $alias => $relation;
            }
        }
    }

    /**
     * @return \Generator
     */
    public function hasManyMergeRelations()
    {
        /** @var \Bardoqi\Sight\Relations\Relation $relation */
        foreach ($this->data as $alias => $relation) {
            if (RelationEnum::HAS_MANY_MERGE == $relation->relation_type) {
                yield $alias => $relation;
            }
        }
    }

    /**
     * @return \Generator
     */
    public function hasManySplitRelations()
    {
        /** @var \Bardoqi\Sight\Relations\Relation $relation */
        foreach ($this->data as $alias => $relation) {
            if (RelationEnum::HAS_MANY_SPLIT == $relation->relation_type) {
                yield $alias => $relation;
            }
        }
    }

    /**
     * @return \Generator
     */
    public function hasManyRelations()
    {
        /** @var \Bardoqi\Sight\Relations\Relation $relation */
        foreach ($this->data as $alias => $relation) {
            if (RelationEnum::HAS_MANY == $relation->relation_type) {
                yield $alias => $relation;
            }
        }
    }
}
