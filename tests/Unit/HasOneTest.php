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

use Bardoqi\Sight\Enums\RelationEnum;
use Bardoqi\Sight\Relations\Relation;
use Bardoqi\Sight\Tests\Fixture\JphUserAlbumsPresenter;
use Bardoqi\Sight\Tests\Fixture\Mock;
use Bardoqi\Sight\Tests\Fixture\UserPresenter;
use Bardoqi\Sight\Tests\TestCase;

/**
 * Class HasOneTest.
 */
final class HasOneTest extends TestCase
{
    /** @test */
    public function testPresenterJoin()
    {
        $user_array_string = include dirname(dirname(__DIR__)).'/tests/Fixture/Users.php';
        $user_array = json_decode($user_array_string, true);

        $user = new UserPresenter();
        $users = $user->selectFields(['id', 'username', 'mobile', 'name', 'avatar_id', 'img_url', 'created_at', 'updated_at'])
            ->fromLocal($user_array, 'user');

        $avatar_ids = $users->pluck('avatar_id');

        $image_array_string = include dirname(dirname(__DIR__)).'/tests/Fixture/Images.php';
        $image_array = json_decode($image_array_string, true);

        $users = $users->innerJoinForeign($image_array, 'images', 'id')
            ->onRelation('avatar_id', 'images', 'id')
            ->toArray();

        $this->assertTrue(is_array($users));
        $this->assertTrue(isset($users[0]['img_url']));
    }

    /** @test */
    public function testItemPresenter()
    {
        $user_array_string = include dirname(dirname(__DIR__)).'/tests/Fixture/Users.php';
        $user_array = json_decode($user_array_string, true);

        $user = new UserPresenter();
        $users = $user->selectFields(['id', 'username', 'mobile', 'name', 'avatar_id', 'img_url', 'created_at', 'updated_at'])
            ->fromLocalItem($user_array[0], 'user');

        $avatar_ids = $users->pluck('avatar_id');

        $image_array_string = include dirname(dirname(__DIR__)).'/tests/Fixture/Images.php';
        $image_array = json_decode($image_array_string, true);

        $users = $users->innerJoinForeign($image_array, 'images', 'id')
            ->onRelationbyObject(
                Relation::fromArray([
                    'local_alias' => $users->local_alias,
                    'local_field' => 'avatar_id',
                    'foreign_alias' => 'images',
                    'foreign_field' => 'id',
                    'relation_type' => RelationEnum::HAS_ONE,
                ])
            )
            ->toItemArray();

        $this->assertTrue(is_array($users));
        $this->assertTrue(isset($users['img_url']));
    }

    /** @test */
    public function testPresenterJoinGetItem()
    {
        $user_array_string = include dirname(dirname(__DIR__)).'/tests/Fixture/Users.php';
        $user_array = json_decode($user_array_string, true);

        $user = new UserPresenter();
        $users = $user->selectFields(['id', 'username', 'mobile', 'name', 'avatar_id', 'img_url', 'created_at', 'updated_at'])
            ->fromLocal($user_array, 'user');

        $avatar_ids = $users->pluck('avatar_id');

        $image_array_string = include dirname(dirname(__DIR__)).'/tests/Fixture/Images.php';
        $image_array = json_decode($image_array_string, true);
        $users = $users->innerJoinForeign($image_array, 'images', 'id')
            ->onRelation('avatar_id', 'images', 'id')
            ->addFieldMappingList($users->list_mappings)
            ->toArray();
        $this->assertTrue(is_array($users));
        $this->assertTrue(isset($users[0]['img_url']));
    }

    /** @test */
    public function testPresenterJoinGetMapItem()
    {
        $user_array_string = include dirname(dirname(__DIR__)).'/tests/Fixture/Users.php';
        $user_array = json_decode($user_array_string, true);

        $user = new UserPresenter();
        $users = $user->selectFields(['id', 'username', 'mobile', 'name', 'avatar', 'created_at', 'updated_at'])
            ->fromLocal($user_array, 'user');

        $avatar_ids = $users->pluck('avatar_id');

        $image_array_string = include dirname(dirname(__DIR__)).'/tests/Fixture/Images.php';
        $image_array = json_decode($image_array_string, true);
        $users = $users->innerJoinForeign($image_array, 'images', 'id')
            ->onRelation('avatar_id', 'images', 'id')
            ->addFieldMappingList($users->list_avatar_mappings)
            ->toArray();
        $this->assertTrue(is_array($users));
        $this->assertTrue(isset($users[0]['avatar']));
    }

    /* @test */
    public function testHasOneEmptyRecord()
    {
        $user_array = Mock::getLocalData(Mock::USER_DATA);
        $user = new JphUserAlbumsPresenter();

        $albums_array = json_decode('[{"userId":0,"id":0,"title":""}]', true);

        $users = $user->selectFields($user->list_fields)
            ->fromLocal($user_array, 'user')
            ->outerJoinForeign($albums_array, 'albums', 'userId')
            ->onRelationbyObject(
                Relation::of()
                    ->localAlias('user')
                    ->localField('id')
                    ->foreignAlias('albums')
                    ->foreignField('userId')
                    ->relationType(RelationEnum::HAS_ONE)
            )
            ->addFieldMappingList($user->list_mapping)
            ->toArray();
        $this->assertTrue(isset($users[0]['albums_id']));
        $this->assertTrue(isset($users[0]['albums_title']));
    }

    /* @test */
    public function testHasOneInnerJoinEmptyRecord()
    {
        $user_array = Mock::getLocalData(Mock::USER_DATA);
        $user = new JphUserAlbumsPresenter();

        $albums_array = json_decode('[{"userId":0,"id":0,"title":""}]', true);

        $users = $user->selectFields($user->list_fields)
            ->fromLocal($user_array, 'user')
            ->innerJoinForeign($albums_array, 'albums', 'userId')
            ->onRelationbyObject(
                Relation::of()
                    ->localAlias('user')
                    ->localField('id')
                    ->foreignAlias('albums')
                    ->foreignField('userId')
                    ->relationType(RelationEnum::HAS_ONE)
            )
            ->addFieldMappingList($user->list_mapping)
            ->toArray();
        $this->assertTrue(empty($users));
    }
}
