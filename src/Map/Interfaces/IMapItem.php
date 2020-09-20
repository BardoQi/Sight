<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: bardo
 * Date: 2020-09-11
 * Time: 15:20.
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
