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
use Bardoqi\Sight\Enums\JoinTypeEnum;
use Bardoqi\Sight\Map\Interfaces\IMap;

/**
 * Class SingleMap.
 */
final class SingleMap extends AbstractList implements IMap
{
    /**
     * $keyed_by : Indicating which key is used for sort;.
     *
     * @var string
     */
    public $keyed_by = '';

    /**
     * $join_type : Keep the join type.
     *
     * @var int
     */
    public $join_type = 0;

    /**
     * @var array
     */
    private $empty_item = null;

    /**
     * Create a instance.
     *
     * @param null|array  $data
     * @param null|string $keyed_by
     *
     * @return static
     * @static
     */
    public static function of($data = null, $keyed_by = null, $join_type = JoinTypeEnum::INNER_JOIN)
    {
        $instance = new static();
        $instance->join_type = $join_type;
        $instance->keyed_by = $keyed_by;
        if (null !== $keyed_by) {
            foreach ($data as $item) {
                $instance->data[$item[$keyed_by]] = $item;
            }

            return $instance;
        }
        $instance->data = $data;

        return $instance;
    }

    /**
     * @return \Generator
     */
    public function getLocalOne()
    {
        foreach ($this->data as $key => $item) {
            yield $key => SingleMapItem::of($item, $this->keyed_by, 0);
        }
    }

    /**
     * @return \Generator
     */
    public function listItems()
    {
        foreach ($this->data as $key => $item) {
            yield $key => SingleMapItem::of($item, $this->keyed_by, 0);
        }
    }

    /**
     * @return \Generator
     */
    public function singleListItems()
    {
        foreach ($this->data as $key => $item) {
            yield $key => SingleMapItem::of($item, $this->keyed_by, 0);
        }
    }

    /**
     * @param $name
     *
     * @return bool
     */
    public function hasColumn($name)
    {
        // get the first item.
        $item = reset($this->data);
        return isset($item[$name]);
    }
}
