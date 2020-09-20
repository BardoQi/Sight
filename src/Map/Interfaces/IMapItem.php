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

interface IMapItem
{
    /**
     * @param $data
     * @param $keyed_by
     * @param $relation_type
     *
     * @return mixed
     */
    public static function of($data, $keyed_by, $relation_type);

    /**
     * @param $path
     * @param $offset
     *
     * @return mixed
     */
    public function findByPath($path, $offset = null);

    /**
     * @param $name
     *
     * @return mixed
     */
    public function hasColumn($name);

    /**
     * @param     $column_name
     * @param int $offset
     *
     * @return mixed
     */
    public function getItemValue($column_name, $offset = null);
}
