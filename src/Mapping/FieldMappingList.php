<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: bardo
 * Date: 2020-08-29
 * Time: 0:46
 */

namespace Bardoqi\Sight\Mapping;

use ArrayAccess;
use Iterator;
use Countable;
use Bardoqi\Sight\Enums\MappingTypeEnum;
use Bardoqi\Sight\Exceptions\InvalidArgumentException;
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

    }

    /**
     * @param     $mapping_key
     * @param     $mapping_source
     * @param int $source_type
     *
     * @return $this
     */
    public function addMapping($mapping_key,$mapping_source,$source_type = MappingTypeEnum::TYPE_FIELD_NAME){
        $mapping = FieldMapping::of($mapping_key,$mapping_source,$source_type);
        if($mapping->isValid()){
            $this->data[$mapping_key] = $mapping;
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
            $this->data[$mapping->mapping_key] = $mapping;
        }
        return $this;
    }

}
