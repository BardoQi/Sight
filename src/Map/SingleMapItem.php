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

use Bardoqi\Sight\Abstracts\AbstractMapItem;
use Bardoqi\Sight\Exceptions\InvalidArgumentException;
use Bardoqi\Sight\Map\Interfaces\IMapItem;

/**
 * Class SingleMapItem.
 */
class SingleMapItem extends AbstractMapItem implements IMapItem
{
    /**
     * Find the row with specified path which is dot-separated string.
     * The function is be called from CombineItem::findByPath.
     *
     * @param array $path
     *
     * @return mixed
     */
    public function findByPath($path, $offset = 0)
    {
        // First we should get the root key as field name
        $key = array_shift($path);
        if (! array_key_exists($key, $this->data)) {
            throw InvalidArgumentException::UndefinedOffset($key);
        }
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
        return array_key_exists($name, $this->data);
    }

    /**
     * @param      $column_name
     * @param null $offset
     *
     * @return mixed|null
     */
    public function getItemValue($column_name, $offset = null)
    {
        if (array_key_exists($column_name, $this->data)) {
            return $this->data[$column_name];
        }

        return false;
    }
}
