<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: bardo
 * Date: 2020-09-21
 * Time: 19:20
 */

namespace Bardoqi\Sight\Map\Interfaces;

/**
 * Class IMap
 *
 * @package Bardoqi\Sight\Map\Interfaces
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
