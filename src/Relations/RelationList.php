<?php

/*
 * This file is part of the bardoqi/sight package.
 *
 * (c) BardoQi <67158925@qq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Bardoqi\Sight\Relations;

use Bardoqi\Sight\Abstracts\AbstractList;

/**
 * Class RelationList
 *
 * @package Bardoqi\Sight\Relations
 */
final class RelationList extends AbstractList
{
    /**
     * FieldMapping Constructor
     *
     *
     */
    public function __construct()
    {

    }

    /**
     *
     * @return \Bardoqi\Sight\Mapping\FieldMapping
     */
    public static function of(){
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
    public function addRelation($local_alias,
                                $local_field,
                                $foreign_alias,
                                $foreign_field,
                                $relation_type
    ){

        $this->data[] = Relation::of(
            $local_alias,
            $local_field,
            $foreign_alias,
            $foreign_field,
            $relation_type);
        return $this;
    }

    /**
     * @param \Bardoqi\Sight\Relations\Relation $relation
     *
     * @return $this
     */
    public function addRelationByObject(Relation $relation){
        if($relation->isValid()){
            $this->data[] = $relation;
        }
        return $this;
    }

}
