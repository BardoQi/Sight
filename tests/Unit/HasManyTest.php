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
use Bardoqi\Sight\Tests\Fixture\BlogPresenter;
use Bardoqi\Sight\Tests\Fixture\JphUserAlbumsPresenter;
use Bardoqi\Sight\Tests\Fixture\Mock;
use Bardoqi\Sight\Tests\TestCase;

/**
 * Class HasManyTest.
 */
final class HasManyTest extends TestCase
{
    /** @test */
    public function testPresenterJoin()
    {
        $blog_array_string = include dirname(dirname(__DIR__)).'/tests/Fixture/Blogs.php';
        $blog_array = json_decode($blog_array_string, true);

        $Blog = new BlogPresenter();
        $blog = $Blog->selectFields(['id', 'title', 'text', 'images', 'created_at', 'created_by'])
            ->fromLocal($blog_array, 'blog');

        $user_array_string = include dirname(dirname(__DIR__)).'/tests/Fixture/Users.php';
        $user_array = json_decode($user_array_string, true);

        $user_ids = $blog->pluck('created_by');
        //You can check the $user_ids result with:
        // print_r($user_ids);
        $blog = $blog->innerJoinForeign($user_array, 'user')
            ->onRelation('created_by', 'user', 'id');

        $image_array_string = include dirname(dirname(__DIR__)).'/tests/Fixture/Images.php';
        $image_ids = $blog->pluck('images');
        //You can check the $image_ids result with:
        // print_r($image_ids);
        $image_array = json_decode($image_array_string, true);

        $blog = $blog->outerJoinForeign($image_array, 'image')
            ->onRelation('images', 'image', 'id', RelationEnum::HAS_MANY_SPLIT);

        $blog->addFieldMappingList(
            [
                'created_by' => ['src' => 'name', 'type' => MappingTypeEnum::JOIN_FIELD, 'alias' => 'user'],
                'images' => ['src' => 'images', 'type' => MappingTypeEnum::METHOD_NAME],
            ]
        );

        $blogs = $blog->toArray();
        $this->assertTrue(is_array($blogs));
        //You can check the $blogs result with:
        // print_r($blogs);
        $this->assertTrue(is_array($blogs[0]['images']));
    }

    /** @test */
    public function testHasManyJoin()
    {
        $user_array = Mock::getLocalData(Mock::USER_DATA);
        $user = new JphUserAlbumsPresenter();

        $albums_array = Mock::getLocalData(Mock::ALNUMS_DATA);

        $users = $user->selectFields($user->list_fields)
            ->fromLocal($user_array)
            ->outerJoinForeign($albums_array, 'albums', 'userId')
            ->onRelation('id', 'albums', 'userId')
            ->addFieldMappingList($user->list_mapping)
            ->toArray();
        $this->assertTrue(isset($users[0]['albums_id']));
        $this->assertTrue(isset($users[0]['albums_title']));
    }

    /* @test */
    public function testHasManyMerge()
    {
        $user_array = Mock::getLocalData(Mock::USER_DATA);
        $user = new JphUserAlbumsPresenter();

        $albums_array = Mock::getLocalData(Mock::ALNUMS_DATA);

        $users = $user->selectFields($user->list_merge_fields)
            ->fromLocal($user_array)
            ->outerJoinForeign($albums_array, 'albums', 'userId')
            ->onRelation('id', 'albums', 'userId', RelationEnum::HAS_MANY_MERGE)
            ->addFieldMappingList($user->list_merge_mapping)
            ->toArray();
        $this->assertTrue(isset($users[0]['albums']));
        $this->assertTrue(isset($users[0]['albums'][0]['id']));
    }
}
