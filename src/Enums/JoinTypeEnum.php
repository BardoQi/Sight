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
 * Class JoinTypeEnum
 *
 * @package Bardoqi\Sight\Enums
 */
final class JoinTypeEnum
{
    const HAS_ONE_INNER = 0;
    const HAS_MANY_INNER = 1;
    const HAS_ONE_OUTER = 2;
    const HAS_MANY_OUTER = 3;
}
