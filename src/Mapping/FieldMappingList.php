<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: bardo
 * Date: 2020-08-29
 * Time: 0:46
 */

namespace Bardoqi\Sight\Mapping;

use Bardoqi\Sight\Enums\MappingTypeEnum;
use Bardoqi\Sight\Abstracts\AbstractList;

/**
 * Class FieldMappingList
 *
 * @package Bardoqi\Sight\Mapping
 */
class FieldMappingList extends AbstractList
{
    /**
     * FieldMapping Constructor
     *
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *
     * @return \Bardoqi\Sight\Mapping\static
     */
    public static function of(){
        return new static();
    }

    /**
     * @param     $key
     * @param     $src
     * @param int $type
     * @param     $alias
     *
     * @return $this
     */
    public function addMapping($key,$src,$type = MappingTypeEnum::FIELD_NAME, $alias='' ){
        $mapping = FieldMapping::of($key,$src,$type,$alias);
        if($mapping->isValid()){
            $this->data[$key] = $mapping;
        }
        return $this;
    }

    /**
     * @param \Bardoqi\Sight\Mapping\FieldMapping $mapping
     *
     * @return $this
     */
    public function addMappingByObject(FieldMapping $mapping){
        if($mapping->isValid()){
            $this->data[$mapping->key] = $mapping;
        }
        return $this;
    }

    /**
     * @param mixed ...$param
     *
     * @return $this
     */
    public function addItem(...$param){
        if($param[0] instanceof FieldMapping){
            return $this->addMappingByObject($param[0]);
        }
        return $this->addMapping(...$param);
    }

}
