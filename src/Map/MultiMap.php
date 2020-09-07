<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: bardo
 * Date: 2020-09-06
 * Time: 10:45
 */

namespace Bardoqi\Sight\Map;
use Bardoqi\Sight\Abstracts\AbstractList;

/**
 * Class MultiMap
 *
 * @package Bardoqi\Sight\Abstracts
 */
class MultiMap extends AbstractList
{
    /**
     * $keyed_by : Indicating which key is used for sort;
     *
     * @var string
     * @access public
     */
    public $keyed_by = '';

    /**
     * @param $offset
     * @param $item
     *
     * @return void
     */
    public function addItem($item, $offset = null){
        if(null === $offset){
            $this->$this->data[] = $item;
            return true;
        }
        if(isset($this->data[$offset])){
            if($this->data[$offset]){
                $this->data[$offset]->addItem(MultiMapItem::of($item), $offset);
                return true;
            }
        }
        $this->data[$offset] = MultiMap::of()->addItem(MultiMapItem::of($item));
        return true;
    }

    /**
     * @param null $data
     * @param null $keyed_by
     *
     * @return void
     */
    protected function init($data = null,$keyed_by = null){
        if((null !== $data) && (null !== $keyed_by)){
            foreach($src_array as $item){
                $this->addItem($item, [$item[$key]]);
            }
            ksort($this->data);
            return true;
        }
        $this->data = $data;
    }

    /**
     * Create a instance
     *
     * @param null|array $data
     * @param null|string $keyed_by
     * @return static
     * @static
     */
    public static function of($data = null,$keyed_by = null){
        $instance = new static($data,$keyed_by);
        $instance->init($data,$keyed_by);
        return $instance;
    }

    /**
     *
     * @param $offset
     *
     * @return mixed
     */
    public function getItem($offset = null){
        if(null !== $offset){
            return $this->data[$offset];
        }
        return reset($this->data);
    }


    /**
     * Create a empty row with given offset
     * @param null $offset
     *
     * @return array
     */
    public function createEmpty($offset = null){
        $item = $this->getItem($offset);
        if($item instanceof MultiMap){
            return $item->createEmpty();
        }
        $keys = $item->getKeys();
        $values = array_fill(0, count($keys), $default);
        return array_combine($keys, $values);
    }

    /**
     * Find the row with specified path which is dot-separated string.
     * @param string $offset
     * @param array $path
     * @return mixed
     */
    public function findByPath($offset,$path){
        $item = $this->getItem($offset);
        return $item->findByPath($path);
    }

}
