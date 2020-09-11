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
use Bardoqi\Sight\Enums\JoinTypeEnum;

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
     * $join_type : Keep the join type.
     *
     * @var int
     * @access public
     */
    public $join_type = 0;

    /**
     * @var array
     */
    private $empty_item = null;

    /**
     * Create a instance
     *
     * @param null|array $data
     * @param null|string $keyed_by
     * @return static
     * @static
     */
    public static function of($data = null,$keyed_by = null,$join_type=JoinTypeEnum::INNER_JOIN){
        $instance = new static();
        $instance->join_type = $join_type;
        if(null !== $keyed_by){
            foreach($data as $item){
                $instance->data[$item[$keyed_by]][] = $item;
            }
            return $instance;
        }
        $instance->data = $data;
        return $instance;
    }

    /**
     * @return array
     */
    public function getAnyOne(){
        $item = reset($this->data);
        if(isset($item[0]))
            return $item[0];
        return $item;
    }

    /**
     * Create a empty row with given offset
     * @param null $offset
     *
     * @return array
     */
    public function getEmptyOne(){
        if(null === $this->empty_item){
            $item = $this->getAnyOne();
            $keys = array_keys($item);
            $values = array_fill(0, count($keys), '');
            $this->empty_item = array_combine($keys, $values);
        }
        return $this->empty_item;
    }

    /**
     * @return \Generator
     */
    public function getLocalOne(){
        foreach($this->data as $key => $item){
            yield $key => SingleMapItem::of($item);
        }
    }

    /**
     * @param $offset
     *
     * @return \Bardoqi\Sight\Map\MultiMapItem|null
     */
    public function getHasOne($offset){
        if(!isset($this->data[$offset])){
            if(JoinTypeEnum::INNER_JOIN === $this->join_type){
                return null;
            }
            return SingleMapItem::of($this->getEmptyOne());
        }
        return SingleMapItem::of($this->data[$offset][0]);
    }

    /**
     * @param $offset
     *
     * @return \Bardoqi\Sight\Map\SingleMapItem|null
     */
    public function getHasManyMerge($offset){
        if(!isset($this->data[$offset])){
            if(JoinTypeEnum::INNER_JOIN === $this->join_type){
                return null;
            }
            $item[0] = $this->getEmptyOne();
            return MultiMapItem::of($item);
        }
        return MultiMapItem::of($this->data[$offset]);
    }

    /**
     * @param $offset
     *
     * @return bool|\Generator
     */
    public function getItems($offset){
        if(!isset($this->data[$offset])){
            return false;
        }
        foreach($this->data[$offset] as $key => $item){
            yield $key => MultiMapItem::of($item);
        }
    }

    /**
     * @return \Generator
     */
    public function listItems(){
        foreach($this->data as $key => $item){
            yield $key => MultiMapItem::of($item);
        }
    }

    /**
     * @return \Generator
     */
    public function singleListItems(){
        foreach($this->data as $key => $item){
            yield $key => SingleMapItem::of($item);
        }
    }
}
