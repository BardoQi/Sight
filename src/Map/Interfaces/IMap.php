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

namespace Bardoqi\Sight\Map\Interfaces;

/**
 * Class IMap.
 */
interface IMap
{
    /**
     * @param null $data
     * @param null $keyed_by
     * @param      $join_type
     *
     * @return mixed
     */
    public static function of($data = null, $keyed_by = null, $join_type = JoinTypeEnum::INNER_JOIN);

    /**
     * @param $name
     *
     * @return mixed
     */
    public function hasColumn($name);
}
