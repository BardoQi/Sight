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

namespace Bardoqi\Sight\Abstracts;

use Bardoqi\Sight\Exceptions\InvalidArgumentException;

/**
 * Class AbstractMapItem
 */
abstract class AbstractMapItem extends AbstractList
{
    /**
     * @var string
     */
    public $keyed_by = '';

    /**
     * @var int
     */
    public $relation_type = 0;

    /**
     * Create a instance.
     *
     * @param null|array  $data
     * @param null|string $keyed_by
     * @param int         $relation_type
     *
     * @return static
     * @static
     */
    public static function of($data, $keyed_by = '', $relation_type = 0)
    {
        $instance = new static();
        $instance->data = $data;
        $instance->keyed_by = $keyed_by;
        $instance->relation_type = $relation_type;

        return $instance;
    }

    /**
     * @param $list
     * @param $key
     *
     * @return array
     */
    protected function getItemBykey($list, $key)
    {
        if (array_key_exists($key, $list)) {
            return $list[$key];
        }

        throw InvalidArgumentException::UndefinedOffset($key);
    }
}
