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
 * Class PaginateTypeEnum.
 */
final class PaginateTypeEnum
{
    /**
     * paginate data for api applications.
     */
    const PAGINATE_API = 0;

    /**
     * paginate data for web applications.
     */
    const PAGINATE_WEB = 1;
}
