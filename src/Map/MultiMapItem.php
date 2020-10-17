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
 * Class MultiMapItem.
 */
class MultiMapItem extends AbstractMapItem implements IMapItem
{
    /**
     * @param $name
     *
     * @return bool
     */
    public function hasColumn($name)
    {
        $item = reset($this->data);

        return array_key_exists($name, $item);
    }

    /**
     * @param      $column_name
     * @param null $offset
     *
     * @return mixed|null
     */
    public function getItemValue($column_name, $offset = null)
    {
        $item = reset($this->data);
        if (null !== $offset) {
            if (isset($this->data[$offset])) {
                $item = $this->data[$offset];
            }
        }
        if (array_key_exists($column_name, $item)) {
            return $item[$column_name];
        }

        return false;
    }

    /**
     * @return \Generator
     */
    public function hasManyOffsets()
    {
        foreach ($this->data as $offset => $item) {
            yield $offset;
        }
    }
}
