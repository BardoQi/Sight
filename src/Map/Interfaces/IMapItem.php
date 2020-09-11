<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: bardo
 * Date: 2020-09-11
 * Time: 15:20
 */

namespace Bardoqi\Sight\Map\Interfaces;

interface IMapItem
{
    /**
     * @param $data
     *
     * @return mixed
     */
    public static function of($data);

    /**
     * @param $path
     * @param $offset
     *
     * @return mixed
     */
    public function findByPath($path,$offset = null);

    /**
     * @return mixed
     */
    public function getKeys();

    /**
     * @param $key
     *
     * @return mixed
     */
    public function hasKey($key);

    /**
     * @param     $key
     * @param int $offset
     *
     * @return mixed
     */
    public function getItemValue($key,$offset = null);
}
