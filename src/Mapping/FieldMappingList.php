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

namespace Bardoqi\Sight\Mapping;

use Bardoqi\Sight\Abstracts\AbstractList;
use Bardoqi\Sight\Enums\MappingTypeEnum;

/**
 * Class FieldMappingList.
 */
class FieldMappingList extends AbstractList
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
     * @param     $key
     * @param     $src
     * @param int $type
     * @param     $alias
     *
     * @return $this
     */
    public function addMapping($key, $src, $type = MappingTypeEnum::FIELD_NAME, $alias = '')
    {
        $mapping = FieldMapping::of($key, $src, $type, $alias);
        if ($mapping->isValid()) {
            $this->data[$key] = $mapping;
        }

        return $this;
    }

    /**
     * @param \Bardoqi\Sight\Mapping\FieldMapping $mapping
     *
     * @return $this
     */
    public function addMappingByObject(FieldMapping $mapping)
    {
        if ($mapping->isValid()) {
            $this->data[$mapping->key] = $mapping;
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
        if ($param[0] instanceof FieldMapping) {
            return $this->addMappingByObject($param[0]);
        }

        return $this->addMapping(...$param);
    }
}
