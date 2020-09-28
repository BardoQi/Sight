<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: bardo
 * Date: 2020-09-29
 * Time: 0:31.
 */

namespace Bardoqi\Sight\Tests\Fixture;

use Bardoqi\Sight\Enums\MappingTypeEnum;
use Bardoqi\Sight\Presenter;

/**
 * Class JphUserAlbums.
 */
final class JphUserAlbumsPresenter extends Presenter
{
    public $list_fields = [
        'id', 'name', 'username', 'email', 'address', 'phone', 'website', 'company',
        'albums_id', 'albums_title',
    ];

    public $list_mapping = [
        'albums_id' => ['src' => 'id', 'type' => MappingTypeEnum::JOIN_FIELD, 'alias' => 'albums'],
        'albums_title' => ['src' => 'title', 'type' => MappingTypeEnum::JOIN_FIELD, 'alias' => 'albums'],
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
