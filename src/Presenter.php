<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: bardo
 * Date: 2020-08-28
 * Time: 13:41
 */

namespace Bardoqi\Sight;

use Bardoqi\Sight\Abstracts\MultiMap;
use Bardoqi\Sight\DataFormaters\DataFormatter;
use Bardoqi\Sight\Enums\PaginateTypeEnum;
use Bardoqi\Sight\Mapping\FieldMapping;
use Bardoqi\Sight\Abstracts\AbstractPresenter;
use Bardoqi\Sight\Enums\MappingTypeEnum;
use Bardoqi\Sight\Exceptions\InvalidArgumentException;
use Bardoqi\Sight\Traits\PresenterTrait;
use Bardoqi\Sight\Relations\Relation;
/**
 * Class Presenter
 *
 * @package Bardoqi\Sight
 */
class Presenter extends AbstractPresenter
{
    use PresenterTrait;

    /**
     * Presenter Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * @param array|string $field_list
     *
     * @return $this
     */
    public function selectFields($field_list){
        if((is_string($field_list)) &&
            (false !== strpos($field_list, ','))){
            $field_list = explode(',',$field_list);
        }
        $this->field_list = $field_list;
        return $this;
    }

    /**
     * @param array|Collection $data_list
     * @param string $alias
     * @param null|string $data_path  It is for the data from elasticsearch
     *
     * @return $this
     */
    public function fromLocal($data_list,$alias,$data_path = null){
        if(empty($alias)){
            Throw InvalidArgumentException::AliasCanNotBeEmpty();
        }
        if(null !== $data_path){ // maybe id is elasticsearch result
            $data_list = Arr::get($data_list,$data_path);
        }
        if(!is_array($data_list)){
            throw InvalidArgumentException::ParamaterIsNotArray();
        }
        $data_list = $this->peelPaginator($data_list);
        $this->local_alias = $alias;
        $this->local_list = MultiMap::of($data_list);
        return $this;
    }

    /**
     * pluck the values from given field
     * support the comma sepereted values
     *
     * @param mixed ...$fields
     *
     * @return array
     */
    public function pluck(...$fields){
        if(!is_array($fields)){
            $fields = [$fields];
        }
        $out_array=[];
        foreach($this->local_list as $item){
            foreach($fields as $key){
                $out_array[] = $item[$key];
            }
        }
        /** maybe the value is comma sepereted values */
        $out_str = implode(',',$out_array);
        $out_array = explode(',',$out_str);
        return array_unique($out_array);
    }

    /**
     * Array join fonction for setting the relations.
     *
     * @param array|Collection $data_list
     * @param string $alias
     * @param string $keyed_by
     *
     * @return $this
     */
    public function joinForeign($data_list,$alias,$keyed_by){
        $this->addJoinList($data_list,$alias,$keyed_by);
        return $this;
    }

    /**
     * @param        $local_alias
     * @param        $local_field
     * @param        $foreign_alias
     * @param        $foreign_field
     * @param        $join_type
     * @return $this
     */
    public function onRelation(
                    $local_alias,
                    $local_field,
                    $foreign_alias,
                    $foreign_field,
                    $join_type
    ){
        $this->addRelation(
            $local_alias,
            $local_field,
            $foreign_alias,
            $foreign_field,
            $join_type
            );
        return $this;
    }

    /**
     * @param \Bardoqi\Sight\Relations\Relation $relation
     *
     * @return $this
     */
    public function onRelationbyObject(Relation $relation){
        $this->relations->addRelationbyObject($relation);
        return $this;
    }

    /**
     * @param     $mapping_key
     * @param     $mapping_source
     * @param int $source_type
     *
     * @return $this
     */
    public function addFieldMapping($mapping_key,$mapping_source,$source_type = MappingTypeEnum::TYPE_FIELD_NAME){
        $this->field_mapping->addMapping($mapping_key,$mapping_source,$source_type);
        return $this;
    }

    /**
     * @param \Bardoqi\Sight\Mapping\FieldMapping $mapping
     *
     * @return $this
     */
    public function addFieldMappingbyObject(FieldMapping $mapping)
    {
        /** @var \Bardoqi\Sight\Mapping\FieldMappingList */
        $this->field_mapping->addMappingWithObject($mapping);
        return $this;
    }

    /**
     * @param $mapping
     * format of $mapping:
     *  [
     *       ['mapping_key' => ['mapping_source'=>a, 'source_type'=>b  ]],
     *       ['mapping_key' => ['mapping_source'=>a, 'source_type'=>b  ]],
     *  ]
     *
     * @return $this
     */
    public function setMappingList($mapping){
        $this->mapping_list = $mapping;
        return $this;
    }

    /**
     * @param $name
     * @param $callback
     *
     * @return $this
     */
    public function addFormatter($name,$callback){
        $this->data_formatter->addFunction($name,$callback);
        return $this;
    }

    /**
     *
     * @return array
     */
    public function toArray(){
        $out_array = [];
        foreach($this->listItems() as $offset => $items){
            $out_array[] = $this->transform($items,$offset);
        }
        return $out_array;
    }

    /**
     * @param int $paginate_type
     *
     * @return array
     */
    public function toPaginateArray($paginate_type = PaginateTypeEnum::PAGINATE_API){
        $out_array = $this->toArray();
        $result = $this->getPaginData($paginate_type);
        $result['data'] = $out_array;
        return $result;
    }

    /**
     * @param $src_array
     * @param $parent_id_key
     *
     * @return array
     */
    protected function toTreeArray($src_array,$parent_id_key = 'parent_d'){
        $out_array = [];
        $ref_array = [];
        foreach($src_array as $key => $item){
            $parent_id = $item[$parent_id_key];
            $item['is_leaf'] = 1;
            $item['children'] = [];
            $id = $item['id'];

            if(0 == $parent_id){
                $ref_array[$id] = & $out_array[];
                $ref_array[$id] = $item;
                continue;
            }
            $ref_array[$id] = & $ref_array[$parent_id]['children'][];
            $ref_array[$id]  = $item;
            $ref_array[$parent_id]['is_leaf'] = 0;
        }
        return $out_array;
    }

}
