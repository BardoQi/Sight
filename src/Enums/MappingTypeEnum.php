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

namespace Bardoqi\Sight\Enums;

/**
 * Class MappingType
 *
 * @package Bardoqi\Sight\Enums
 */
final class MappingTypeEnum
{
    /**
     * Access the value via field name
     */
    const FIELD_NAME = 0;

    /**
     * Transform the value via method of data convert.
     */
    const DATA_FORMATER = 1;

    /**
     * Access the value via method name
     */
    const METHOD_NAME = 2;

    /**
     * Access the value via path of array. for instance： “a.b.c”
     */
    const ARRAY_PATH = 3;

    /**
     * @param $value
     *
     * @return bool
     */
    public static function valid($value){
        return (isset(([0,1,2,3])[$value]));
    }
}
