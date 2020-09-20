<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: bardo
 * Date: 2020-08-31
 * Time: 0:32
 */

namespace Bardoqi\Sight\Tests\Unit;

require dirname(dirname(dirname(dirname(__DIR__)))).'/autoload.php';
require dirname(__DIR__).'\Fixture\BlogPresenter.php';
require dirname(__DIR__).'\Fixture\UserPresenter.php';
require dirname(__DIR__).'\TestCase.php';

use Bardoqi\Sight\Enums\MappingTypeEnum;
use Bardoqi\Sight\Enums\RelationEnum;
use Bardoqi\Sight\Tests\Fixture\BlogPresenter;
use Bardoqi\Sight\Tests\Fixture\UserPresenter;
use Bardoqi\Sight\Tests\TestCase;

/**
 * Class HasManyTest
 *
 * @package Bardoqi\Sight\Tests\Unit
 */
final class HasManyTest  extends TestCase
{

    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown() {
        parent::tearDown();
    }

    /** @test */
    public function testPresenterJoin(){
        $blog_array_string = include(dirname(dirname(__DIR__))."\\tests\\Fixture\\Blogs.php");
        $blog_array = json_decode($blog_array_string,true);

        $Blog = new BlogPresenter();
        $blog = $Blog->selectFields(["id","title","text","images","created_at","created_by"])
            ->fromLocal($blog_array,'blog');

        $user_array_string = include(dirname(dirname(__DIR__))."\\tests\\Fixture\\Users.php");
        $user_array = json_decode($user_array_string,true);

        $user_ids = $blog->pluck('created_by');
        //You can check the $user_ids result with:
        // print_r($user_ids);
        $blog = $blog->innerJoinForeign($user_array,'user')
            ->onRelation('created_by','user','id');

        $image_array_string = include(dirname(dirname(__DIR__))."\\tests\\Fixture\\Images.php");
        $image_ids = $blog->pluck('images');
        //You can check the $image_ids result with:
        // print_r($image_ids);
        $image_array = json_decode($image_array_string,true);

        $blog = $blog->outerJoinForeign($image_array,'image')
            ->onRelation('images','image','id', RelationEnum::HAS_MANY_SPLIT);

        $blog->addFieldMappingList(
          [
              'created_by' => ['src'=>'name','type'=>MappingTypeEnum::JOIN_FIELD,'alias'=>'user'],
              'images' =>['src'=>'images','type'=>MappingTypeEnum::METHOD_NAME],
          ]
        );

        $blogs = $blog->toArray();
        $this->assertTrue(is_array($blogs));
        //You can check the $blogs result with:
        // print_r($blogs);
        $this->assertTrue(is_array($blogs[0]['images']));
    }
}
