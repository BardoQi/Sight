<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: bardo
 * Date: 2020-08-29
 * Time: 0:30
 */

namespace Bardoqi\Sight\Mapping;

use Bardoqi\Sight\Enums\MappingTypeEnum;
use Bardoqi\Sight\Exceptions\InvalidArgumentException;

/**
 * Class FieldMapping
 *
 * @package Bardoqi\Sight\Mapping
 */
final class FieldMapping
{
    /**
     * The mapping key shoud be same in the field list.
     *
     * @var string
     */
    public $mapping_key;

    /**
     * Where we could get the value
     *
     * @var string
     */
    public $mapping_source;

    /**
     * Define the manaer of getting value
     *
     * @var int
     */
    public $source_type;

    /**
     * @var string
     */
    public $alias;

    /**
     * FieldMapping Constructor
     *
     * @param $mapping_key
     * @param $mapping_source
     * @param $source_type
     *
     */
    public function __construct($mapping_key = '',$mapping_source = '',$source_type = MappingTypeEnum::TYPE_FIELD_NAME)
    {
        $this->mapping_key = $mapping_key;
        $this->mapping_source = $mapping_source;
        $this->source_type = $source_type;
    }

    /**
     * @param $mapping_key
     * @param $mapping_source
     * @param $source_type
     *
     * @return \Bardoqi\Sight\Mapping\FieldMapping
     */
    public static function of($mapping_key = '',$mapping_source = '',$source_type = MappingTypeEnum::TYPE_FIELD_NAME){

        return new static($mapping_key,$mapping_source,$source_type);
    }

    /**
     * @return void
     */
    public function isValid(){
        if(empty($this->mapping_key)){
            throw InvalidArgumentException::MappingKeyCanNotBeEmpty();
        }
        if(empty($this->mapping_source)){
            throw InvalidArgumentException::MappingSourceCanNotBeEmpty();
        }
        if(MappingTypeEnum::valid($this->source_type)){
            throw InvalidArgumentException::MappingTypeIsNotValid();
        }
    }

    /**
     * @param $mapping_key
     *
     * @return mixed
     */
    public function mappingKey($mapping_key = null){
        if(null == $mapping_key){
            return $this->mapping_key;
        }
        $this->mapping_key = $mapping_key;
        return $this;
    }

    /**
     * @param $mapping_source
     *
     * @return mixed
     */
    public function mappingSource($mapping_source = null){
        if(null == $mapping_source){
            return $this->mapping_source;
        }
        $this->mapping_source = $mapping_source;
        return $this;
    }

    /**
     * @param $source_type
     *
     * @return mixed
     */
    public function sourceType($source_type = null){
        if(null == $source_type){
            return $this->source_type;
        }
        $this->source_type = $source_type;
        return $this;
    }

    /**
     * @param $alias
     *
     * @return $this|string
     */
    public function alias($alias){
        if(null == $alias){
            return $this->alias;
        }
        $this->alias = $alias;
        return $this;
    }


}
