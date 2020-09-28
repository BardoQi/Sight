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

namespace Bardoqi\Sight\Map;

use Bardoqi\Sight\Abstracts\AbstractList;
use Bardoqi\Sight\Exceptions\InvalidArgumentException;
use Bardoqi\Sight\Map\Interfaces\IMapItem;

/**
 * Class SingleMapItem.
 */
class SingleMapItem extends AbstractList implements IMapItem
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
        if (isset($list[$key])) {
            return $list[$key];
        }

        throw InvalidArgumentException::UndefinedOffset($key);
    }

    /**
     * Find the row with specified path which is dot-separated string.
     *
     * @param array $path
     *
     * @return mixed
     */
    public function findByPath($path, $offset = 0)
    {
        $key = array_shift($path);
        $item = $this->data[$key];

        $decode_item = null;
        if (! is_array($item)) {
            $decode_item = json_decode($item, true);
            if (null === $decode_item) {
                throw InvalidArgumentException::ItemIsNotJsonString();
            }
            $this->data[$key] = $decode_item;
            $item = $decode_item;
        }

        foreach ($path as $key) {
            $item = $this->getItemBykey($item, $key);
        }

        return $item;
    }

    /**
     * @param $name
     *
     * @return bool
     */
    public function hasColumn($name)
    {
        return isset($this->data[$name]);
    }

    /**
     * @param      $column_name
     * @param null $offset
     *
     * @return mixed|null
     */
    public function getItemValue($column_name, $offset = null)
    {
        if (isset($this->data[$column_name])) {
            return $this->data[$column_name];
        }

        return false;
    }
}
