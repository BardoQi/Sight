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

namespace Bardoqi\Sight\Tests\Unit;

use Bardoqi\Sight\Enums\MappingTypeEnum;
use Bardoqi\Sight\Enums\RelationEnum;
use Bardoqi\Sight\Exceptions\InvalidArgumentException;
use Bardoqi\Sight\Tests\Fixture\JphUserAlbumsPresenter;
use Bardoqi\Sight\Tests\Fixture\Mock;
use Bardoqi\Sight\Tests\TestCase;

/**
 * Class MappingExceptionTest.
 */
final class MappingExceptionTest extends TestCase
{
    public $list_fields = [
        'id', 'name', 'username', 'email', 'address', 'phone', 'website', 'company',
        'albums_id', 'albums_title',
    ];

    public $list_bad_formatter = [
        'albums_id' => ['src' => 'id', 'type' => MappingTypeEnum::DATA_FORMATER, 'alias' => 'albums'],
        'albums_title' => ['src' => 'title', 'type' => MappingTypeEnum::DATA_FORMATER, 'alias' => 'albums'],
    ];

    public $list_bad_method = [
        'albums_id_a' => ['src' => 'id', 'type' => MappingTypeEnum::METHOD_NAME, 'alias' => 'albums'],
        'albums_title_b' => ['src' => 'title', 'type' => MappingTypeEnum::METHOD_NAME, 'alias' => 'albums'],
    ];

    public $list_bad_arraypath = [
        'albums_id' => ['src' => 'm_id', 'type' => MappingTypeEnum::ARRAY_PATH, 'alias' => 'albums'],
        'albums_title' => ['src' => 'm_title', 'type' => MappingTypeEnum::ARRAY_PATH, 'alias' => 'albums'],
    ];

    public $list_bad_joinfields = [
        'albums_id' => ['src' => 'm_id', 'type' => MappingTypeEnum::JOIN_FIELD, 'alias' => 'albums'],
        'albums_title' => ['src' => 'm_title', 'type' => MappingTypeEnum::JOIN_FIELD, 'alias' => 'albums'],
    ];

    /** @test  */
    public function testMappingExceptions()
    {
        $user_array = Mock::getLocalData(Mock::USER_DATA);
        $albums_array = Mock::getLocalData(Mock::ALNUMS_DATA);
        $user = JphUserAlbumsPresenter::of();
        $users = $user->selectFields($user->list_fields)
            ->fromLocal($user_array)
            ->outerJoinForeign($albums_array, 'albums', 'userId')
            ->onRelation('id', 'albums', 'userId', RelationEnum::HAS_MANY);
        //->addFieldMappingList($user->list_mapping)
        try {
            $users = $users->toArray();
        } catch (\Exception $e) {
            $this->assertTrue($e instanceof InvalidArgumentException);
        }

        $user = JphUserAlbumsPresenter::of();
        $users = $user->selectFields($user->list_fields)
            ->fromLocal($user_array)
            ->outerJoinForeign($albums_array, 'albums', 'userId')
            ->onRelation('id', 'albums', 'userId', RelationEnum::HAS_MANY)
            ->addFieldMappingList($this->list_bad_formatter);
        try {
            $users = $users->toArray();
        } catch (\Exception $e) {
            $this->assertTrue($e instanceof InvalidArgumentException);
        }

        $user = JphUserAlbumsPresenter::of();
        $users = $user->selectFields($user->list_fields)
            ->fromLocal($user_array)
            ->outerJoinForeign($albums_array, 'albums', 'userId')
            ->onRelation('id', 'albums', 'userId', RelationEnum::HAS_MANY)
            ->addFieldMappingList($this->list_bad_method);
        try {
            $users = $users->toArray();
        } catch (\Exception $e) {
            $this->assertTrue($e instanceof InvalidArgumentException);
        }

        $user = JphUserAlbumsPresenter::of();
        $users = $user->selectFields($user->list_fields)
            ->fromLocal($user_array)
            ->outerJoinForeign($albums_array, 'albums', 'userId')
            ->onRelation('id', 'albums', 'userId', RelationEnum::HAS_MANY)
            ->addFieldMappingList($this->list_bad_arraypath);
        try {
            $users = $users->toArray();
        } catch (\Exception $e) {
            $this->assertTrue($e instanceof InvalidArgumentException);
        }

        $user = JphUserAlbumsPresenter::of();
        $users = $user->selectFields($user->list_fields)
            ->fromLocal($user_array)
            ->outerJoinForeign($albums_array, 'albums', 'userId')
            ->onRelation('id', 'albums', 'userId', RelationEnum::HAS_MANY)
            ->addFieldMappingList($this->list_bad_joinfields);
        try {
            $users = $users->toArray();
        } catch (\Exception $e) {
            $this->assertTrue($e instanceof InvalidArgumentException);
        }
    }
}
