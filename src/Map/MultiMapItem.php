<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: bardo
 * Date: 2020-09-06
 * Time: 11:00
 */

namespace Bardoqi\Sight\Map;

use Bardoqi\Sight\Exceptions\InvalidArgumentException;
use Bardoqi\Sight\Abstracts\AbstractList;

/**
 * Class MultiMapItem
 *
 * @package Bardoqi\Sight\Abstracts
 */
class MultiMapItem extends AbstractList
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
     *
     * @return array
     */
    protected function getItemBykey($list,$key){
        if(isset($list[$key])){
            return $list[$key];
        }
        throw InvalidArgumentException::UndefinedOffset($key);
    }

    /**
     * Find the row with specified path which is dot-separated string.
     *
     * @param array $path
     * @return mixed
     */
    public function findByPath($path){
        $key = array_shift($path);
        $item = $this->getItemBykey($this->data,$key);
        if(!is_array($item)){
            $decode = json_decode($item,true);
        }
        if(null === $decode){
            throw InvalidArgumentException::ItemIsNotJsonString();
        }else{
            $this->data[$key] = $decode;
        }
        $item = $decode;
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
    
    
    public function hasKey($key){
        return isset($this->data[$key]);
    }

}
