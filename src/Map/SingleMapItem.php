<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: bardo
 * Date: 2020-09-11
 * Time: 15:08
 */

namespace Bardoqi\Sight\Map;

use Bardoqi\Sight\Abstracts\AbstractList;
use Bardoqi\Sight\Exceptions\InvalidArgumentException;
use Bardoqi\Sight\Map\Interfaces\IMapItem;

/**
 * Class SingleMapItem
 *
 * @package Bardoqi\Sight\Map
 */
class SingleMapItem extends AbstractList  implements IMapItem
{
    /**
     * Create a instance
     *
     * @param null|array $data
     * @param null|string $keyed_by
     * @return static
     * @static
     */
    public static function of($data){
        $instance = new static();
        $instance->data = $data;
        return $instance;
    }

    /**
     * @param $list
     * @param $key

     * @return array
     */
    protected function getItemBykey($list,$key){
        if(isset($this->data[$key])){
            return $this->data[$key];
        }
        throw InvalidArgumentException::UndefinedOffset($key);
    }

    /**
     * Find the row with specified path which is dot-separated string.
     *
     * @param array $path
     * @return mixed
     */
    public function findByPath($path,$offset = 0){
        $key = array_shift($path);
        $item = $this->data;
        if(!is_array($item)){
            $decode_item = json_decode($item,true);
        }
        if(null === $decode_item){
            throw InvalidArgumentException::ItemIsNotJsonString();
        }else{
            $this->data[$key] = $decode_item;
        }
        $item = $decode_item;
        foreach($path as $key){
            $item = $this->getItemBykey($item,$key);
        }
        return $item;
    }

    /**
     * @return array
     */
    public function getKeys(){
        return array_keys($this->data);
    }

    /**
     * @param $key
     *
     * @return bool
     */
    public function hasKey($key){
        return isset($this->data[$key]);
    }

    /**
     * @param      $key
     * @param null $offset
     *
     * @return mixed|null
     */
    public function getItemValue($key,$offset = null){
        if(isset($this->data[$key])){
            return $this->data[$key];
        }
        return false;
    }

}
