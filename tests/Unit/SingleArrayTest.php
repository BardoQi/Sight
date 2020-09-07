<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: bardo
 * Date: 2020-08-31
 * Time: 0:30
 */

namespace Bardoqi\Sight\Tests\Unit;
require dirname(dirname(dirname(dirname(__DIR__)))).'/autoload.php';
use Bardoqi\Sight\Tests\Unit\UserPresenter;
use PHPUnit\Framework\TestCase;

/**
 * Class SingleArrayTest
 *
 * @package Bardoqi\Sight\Tests\Unit
 */
final class SingleArrayTest extends TestCase
{
     public $userPresenter;

     public function testUserPresenterCreate(){

         $user_array_string = include(dirname(dirname(__DIR__))."\\tests\\Fixture\\Users.php");
         $user_array = json_decode($user_array_string,true);
         $user = new UserPresenter();
         $user->selectFields(["id","username","mobile","name","avatar_id","created_at","updated_at"])
             ->fromLocal($user_array,'user')
             ->toArray();
     }
}
