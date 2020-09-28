<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: bardo
 * Date: 2020-09-25
 * Time: 0:54
 */

namespace Bardoqi\Sight\Tests\Fixture;

/**
 * Class Mock
 */
final class Mock
{
    /**
     * @var string
     */
    public static $users = "https://jsonplaceholder.typicode.com/users";

    /**
     * @var string
     */
    public static $alnums = "https://jsonplaceholder.typicode.com/albums";

    /**
     * @var string
     */
    public static $posts = "https://jsonplaceholder.typicode.com/posts";

    /**
     * @var string
     */
    public static $photos = "https://jsonplaceholder.typicode.com/photos";

    /**
     * @var string
     */
    public static $todos = "https://jsonplaceholder.typicode.com/todos";

    /**
     * @var string
     */
    public static $comments = "https://jsonplaceholder.typicode.com/comments";


    public const USER_DATA = DIRECTORY_SEPARATOR .'JphUser.php';

    public const ALNUMS_DATA = DIRECTORY_SEPARATOR .'ALbums.php';


    /**
     * @param $url
     *
     * @return bool|string
     */
    public static function getData($url){

        $response = @file_get_contents($url);

        $data = json_decode($response, true);
        return $data;
    }

    /**
     * @param $table
     *
     * @return mixed
     */
    public static function getLocalData($table){
        $data_array_string = include (__DIR__ . $table);
        $data = json_decode($data_array_string, true);
        return $data;
    }
}
