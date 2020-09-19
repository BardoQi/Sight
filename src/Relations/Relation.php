<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: bardo
 * Date: 2020-08-28
 * Time: 19:17
 */

namespace Bardoqi\Sight\Relations;


use Bardoqi\Sight\Exceptions\InvalidArgumentException;

/**
 * Class Relation
 *
 * @package Bardoqi\Sight\Relations
 */
final class Relation
{
    /**
     * @var string
     */
    public $local_alias;

    /**
     * @var string
     */
    public $local_field;

    /**
     * @var string
     */
    public $foreign_alias;

    /**
     * @var string
     */
    public $foreign_field;

    /**
     * @var int
     */
    public $relation_type;

    /**
     * @param        $local_alias
     * @param        $local_field
     * @param        $foreign_alias
     * @param        $foreign_field
     * @param        $relation_type
     *
     * @return \Bardoqi\Sight\Relations\Relation
     */
    public static function of(            $local_alias,
                                          $local_field,
                                          $foreign_alias,
                                          $foreign_field,
                                          $relation_type
    ){
        $instance = new static();
        $instance->init(
            $local_alias,
            $local_field,
            $foreign_alias,
            $foreign_field,
            $relation_type
        );
        return $instance;

    }

    /**
     * @param        $local_alias
     * @param        $local_field
     * @param        $foreign_alias
     * @param        $foreign_field
     * @param        $relation_type
     *
     * @param string $operator
     *
     * @return void
     */
    private function init(            $local_alias,
                                      $local_field,
                                      $foreign_alias,
                                      $foreign_field,
                                      $relation_type
    ){

        $this->local_alias = $local_alias;
        $this->local_field = $local_field;
        $this->foreign_alias = $foreign_alias;
        $this->foreign_field = $foreign_field;
        $this->relation_type = $relation_type;
    }

    /**
     * @return bool
     */
    public function isValid(){
        if((empty($this->local_alias))
            ||(empty($this->local_field))
            ||(empty($this->foreign_alias))
            ||(empty($this->foreign_field))){
            throw InvalidArgumentException::ParamsOfRelationIsMissing();
        }
        return true;
    }

    /**
     * @param $local_alias
     *
     * @return mixed
     */
    public function localAlias($local_alias = null){
        if(null == $local_alias){
            return $this->local_alias;
        }
        $this->local_alias = $local_alias;
        return true;
    }

    /**
     * @param $local_field
     *
     * @return mixed
     */
    public function localField($local_field = null){
        if(null == $local_field){
            return $this->local_field;
        }
        $this->local_field = $local_field;
        return true;
    }

    /**
     * @param $foreign_alias
     *
     * @return mixed
     */
    public function foreignAlias($foreign_alias = null){
        if(null == $foreign_alias){
            return $this->foreign_alias;
        }
        $this->foreign_alias = $foreign_alias;
        return true;
    }

    /**
     * @param $foreign_field
     *
     * @return mixed
     */
    public function foreignField($foreign_field = null){
        if(null == $foreign_field){
            return $this->foreign_field;
        }
        $this->foreign_field = $foreign_field;
        return true;
    }

    /**
     * @param $relation_type
     *
     * @return mixed
     */
    public function relationType($relation_type= null){
        if(null == $relation_type){
            return $this->relation_type;
        }
        $this->relation_type = $relation_type;
        return true;
    }

    public function __set($name,$value){
        $this->$name = $value;
    }

    /**
     * @param $array_item
     *
     * @return mixed
     */
    public static function fromArray($array_item){
        $instance = new static();
        if(isset($array_item['local_alias'])){
            foreach($array_item as $key => $value){
                $instance->$key = $value;
            }
            return $instance;
        }
        throw InvalidArgumentException::RelationParamaterMustBeKeyValue();
    }
}
