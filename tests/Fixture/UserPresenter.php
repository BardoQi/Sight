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

use App\Enums\ImageCtalogEnum;
use Bardoqi\Sight\Enums\MappingTypeEnum;
use Bardoqi\Sight\Presenter;
use Illuminate\Support\Arr;

/**
 * Class UserPresenter.
 */
final class UserPresenter extends Presenter
{
    public $list_mappings = [
        'avatar_id' => [ 'id', MappingTypeEnum::METHOD_NAME],
        'img_url' => [ 'img_url', MappingTypeEnum::METHOD_NAME],
    ];

    public $list_avatar_mappings = [
        'avatar' => [ 'avatar', MappingTypeEnum::METHOD_NAME],
    ];


    public function __construct()
    {
        parent::__construct();
    }

    public function avatar_id($value)
    {
        return $this->current_item->getItemValue('id',0,'images');
    }

    public function img_url($value)
    {
        return $this->current_item->getItemValue('id',0);
    }

    public function avatar($value)
    {
        $item =  $this->current_item->getMapItem('images');
        $value = [
            'id'=>$item->getItemValue('id'),
            'img_url'=> $item->getItemValue('img_url'),
        ];
        return $value;
    }
}
