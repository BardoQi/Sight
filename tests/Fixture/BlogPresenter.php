<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: bardo
 * Date: 2020-08-31
 * Time: 0:21
 */

namespace Bardoqi\Sight\Tests\Fixture;

use Bardoqi\Sight\Presenter;
use Illuminate\Support\Arr;

/**
 * Class BlogPresenter
 *
 * @package Bardoqi\Tests
 */
final class BlogPresenter extends Presenter
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $value
     *
     * @return void
     */
    public function images($value)
    {
        $images = $this->getCurrentItem()->getData('image');
        $out_array = [];
        foreach ($images as $key => $item) {
            if(is_array($item))
                $out_array[] = Arr::only($item,['id','img_url']);
        }
        return $out_array;
    }
}
