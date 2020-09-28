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

namespace Bardoqi\Sight\Tests\Fixture;

use Bardoqi\Sight\Presenter;
use Illuminate\Support\Arr;

/**
 * Class BlogPresenter.
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
            if (is_array($item)) {
                $out_array[] = Arr::only($item, ['id', 'img_url']);
            }
        }

        return $out_array;
    }
}
