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

namespace Bardoqi\Sight\Enums;

/**
 * Class RelationEnum.
 */
final class RelationEnum
{
    /**
     * There is 1 items in join array.
     */
    const HAS_ONE = 1;

    /**
     * There are many items in join array.
     */
    const HAS_MANY = 2;

    /**
     * There are many items in join array and must to merge to a fields.
     */
    const HAS_MANY_MERGE = 3;

    /**
     * There are many items in join array and must explode the keys in local array.
     */
    const HAS_MANY_SPLIT = 4;
}
