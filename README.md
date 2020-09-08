# Sight
## Your Missing Presenter for Laravel

### About Sight
   
There is no MVP (Model View Presenter) pattern in laravel framework before. 

Now you could using sight, which is in a elegant archiitecture and give you elegant MVP pattern.
   
The difference between MVC and MVP is that, a view is totally passive and unaware of the model layer. While in MVC it isn't passive and aware of the Model Layer. In proper MVP, View class (if it is) also SHOULD NOT implement a constructor.
   
A typical example of MVP will consist of these parts:
* Data Access Layer (DataMappers, ORM etc)
* Business Logic (like validation, and computations)
* Passive view class (it could be a template, but it would be better to stick with a class)
* The presenter that bridges both Model and View
     
And Sight is using to bridge both Business Logic Layer and View or Controller(for API applitation) layer.
     
And Sight help you transform the original data from database query to the former that the view or client could use.     
     
So Sight is a presenter layer for laravel framework.     
     
We believe that you will be more happy during your coding with Sight.

With Sight you need not coding repeat unhappy any more.

With Sight you need not coding to search array any more, because we transform the join array with mapping.

With Sight you need not join many table for on field.

With sight you could stop mew programer to query table in code seagment "for" or "foreach".

With Sight Many unnessary join query weill be avoid.

Enjoy Sight now, please!

### What Sight do?


### Why Sight?

### Features
* Field transform. For instance: int to date, time, datetime ...
* Foreign Key Id transform. For instance: id to name or other fields with array join.
* Single array transform and multiple arrays transform with array join.
* One has One join: For instance, image_id in table article, and id, image_url in table images. You could get image_url simply.
* Many has one Join: For instance, image_ids in table article like '3,5,7,9', and id, image_url in table images. You could get image_url simply.
* Simple pluck function could get the array of foreign key used.   
* Simply hidden the fields that you don't want to show with the method selectFields.
* Field transform could using the data convert, array path and method name.
* Support data quried from both the database such as mysql and elasticsearch with array path converting.
* Support call with static manaer. 
* Support flatten json type data from mysql. 

### Getting Start

#### Install
```php
$ composer require bardoqi/sight
```
#### Code Sample

It is very simple! You just using it like using Model.

The function chain is just like:

```php
    
    $this->selectFields()
        ->fromLocal()
        ->joinForeign()
        ->onRelation()
        ->setMapping()
        ->toArray();

```
Just like in Model Or SQL:
```php
    $this->select()
        ->from()
        ->join()
        ->on()
        ->toArray();
        
        
```

Of course, it is not same as Model at all.

At first you should create a presenter class to extents the Presenter. For Instanace;
```php
namespace App\Presenter

use BardoQi\Sight\Presenter;

class ArticlePresenter extents Presenter
{
   public function __construct(){
       parent::__construct();
   }  
}

``` 
Then you could add a function to get the data.

```php
namespace App\Presenter

use BardoQi\Sight\Presenter;
use App\Repositories\ArticleRepository;
use App\Repositories\UserRepository; 

class ArticlePresenter extents Presenter
{
   public function __construct(){
       parent::__construct();
   }  
   
   public function getArticleList($where)
   {
       $articleArray = ArticleRepository::getList($where);
       $user_ids = $this->selectFields('id','title','created_at','created_by')
            ->fromLocal($articleArray,'articles')
            ->pluck('created_by');
       $users = UserRepository::getUsersWithIds($user_ids);
       $this->joinForeign($users,'userss')
            ->onRelationByObject(Relation::of()
                ->localAlias('articles')
                ->localField('created_by')
                ->foreignAlias('users')
                ->foreighField('id')) 
            ->addFieldMappingWithObject(FieldMapping::of()
                ->mappingKey('created_at')
                ->mappingSource('created_at')
                ->mappingType(MappingTypeEnum::METHOD_NAME))
            ->addFieldMappingWithObject(FieldMapping::of()
                ->mappingKey('created_by')
                ->mappingSource('created_by')
                ->mappingType(MappingTypeEnum::METHOD_NAME));         
       return $this->toPaginateArray(PaginateTypeEnum::PAGINATE_API);
   }
   
}

``` 
  
Then you can call from controller:
  
```php
    return ArticlePresenter::getArticleList($where);    
```

And you also could define the mapping in the property:

```php
    protected $mappig_list = [
         ['created_at' => ['mapping_source'=>'created_at', 'source_type'=>MappingTypeEnum::METHOD_NAME  ]], 
         ['created_by' => ['mapping_source'=>'user_name', 'source_type'=>MappingTypeEnum::METHOD_NAME  ]],
    ];
```

Then the created_by will return the user_name field from the array Users.

Maybe you found that is very simple.  And the fucntion name is the field name.

And if you need the $offset of the array, you could get from  $this->offset();
  
Just all!

#### Document

##### Function List

###### selectFields($field_lists)
   
The $field_lists could be array or comma seperated string. 
   
With the function you could decide that which fields will be show.
   
And you could change the key name with the field mapping.

#### Troubleshortings

#### License
  
MIT

