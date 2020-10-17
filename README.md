# Sight
## Your Missing Presenter for Laravel
 
[![Build Status](https://travis-ci.org/BardoQi/Sight.png?branch=master)](https://travis-ci.org/BardoQi/Sight)
[![StyleCI](https://github.styleci.io/repos/293536215/shield?branch=master)](https://github.styleci.io/repos/293536215?branch=master)
[![Codecov branch](https://img.shields.io/codecov/c/github/BardoQi/Sight/develop.svg?style=flat-square&logo=codecov)](https://codecov.io/github/BardoQi/Sight)
[![Latest Stable Version](https://poser.pugx.org/bardoqi/sight/v)](//packagist.org/packages/bardoqi/sight)
[![Total Downloads](https://poser.pugx.org/bardoqi/sight/downloads)](//packagist.org/packages/bardoqi/sight)
[![License](https://poser.pugx.org/bardoqi/sight/license)](//packagist.org/packages/bardoqi/sight)
 
### About Sight

[中文(Chinese)](https://github.com/BardoQi/Sight/blob/master/README.cn.md)
   
There is no MVP (Model View Presenter) pattern in Laravel framework before. 
   
Maybe you know that there is no presenter layer in server side. But I should tell you, the Sight just is one. 
   
Sight not only for changing the MVC web application to MVP, but also Is a good library for changing the API application.
    
Now you could use Sight, which is in an elegant architecture and give you elegant MVP pattern.
        
The difference between MVC and MVP is that, a view is totally passive and unaware of the model layer. While in MVC it isn't passive and aware of the Model Layer. In proper MVP, View class (if it is) also SHOULD NOT implement a constructor.
   
A typical example of MVP will consist of these parts:
* Data Access Layer (DataMappers, ORM etc)
* Business Logic (like validation, and computations)
* Passive view class (it could be a template, but it would be better to stick with a class)
* The presenter that bridges both Model and View
   
And Sight is using to bridge both Business Logic Layer and View or Controller (for API application) layer.
     
And Sight is a mapping class to help you transform the original data from database query to the former that the view or client could use.     
     
So Sight is a presenter layer for Laravel framework.     
     
We believe that you will be more happier during your coding with Sight.

With Sight you need not coding repeat unhappy any more.

With Sight you need not coding to search array any more, because we transform the join array with mapping.

With Sight you need not join many tables for on field.

With sight you could stop mew programmer to query table in code segment "for" or "foreach".

With Sight Many unnecessary join query will be avoided.

Enjoy Sight now, please!

### What do Sight do?
  
From data layer to view or API layer you need to write many foreach code for the data transform.
   
Now you need not write foreach code any more. Just use "ToArray()"!
   
Maybe the troubleshooting is that you will unable to using "foreach" in future! :)
   

### Why Sight?

Maybe your team member often using database query in the "foreach" block. When you start using Sight, the bad code will be disappeared.
     
We should better avoid too many join queries. If the join only for one field, we should better to do that with many time queries. But then we must do coding more! Now it is simple.
 
Maybe you often using database view to query for trans form many id fields to name. That is just a bad query for database performance and the legibility of the code. With Sight you can prohibit them from using these queries.

With database you could doing hasOne and hasMany join query. But you could not get one record with hasMany join query. You should merge the record manually with coding with query many times. With Sight it will be simple!

Maybe you had used the library like League\Fractal, we recommend you have a try with Sight!

Maybe you use the mutators. But you had need to transform the field after some doing. With Sight you could doing it just before return to controller.

### Features
* Field transform. For instance: int to date, time, datetime ...
* Foreign Key Id transform. For instance: id to name or other fields with array join.
* Single array transforming and multiple arrays transforming with array join.
* One has One join: For instance, image_id in table article, and id, image_url in table images. You could get image_url simply.
* Many has one Join: For instance, image_ids in table article like '3,5,7,9', and id, image_url in table images. You could get image_url simply.
* Simple pluck function could get the array of foreign key used.   
* Simply hidden the fields that you don't want to show with the method selectFields.
* Field transform could use the data convert, array path and method name.
* Support data queried from both the database such as MySQL and ElasticSearch with array path converting.
* Support flatten json type data from MySQL and ElasticSearch.  
* About the array join it support inner join and outer join.
* High performance, because every array only need be traversed twice.  

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
        ->innerJoinForeign()
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

use Bardoqi\Sight\Presenter;

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

use Bardoqi\Sight\Presenter;
use Bardoqi\Sight\Traits\PresenterTrait;
use Bardoqi\Sight\Enums\MappingTypeEnum 
use Bardoqi\Sight\Enums\PaginateTypeEnum 
use App\Repositories\ArticleRepository;
use App\Repositories\UserRepository; 

class ArticlePresenter extents Presenter
{
   use PresenterTrait;

   public function getArticleList($where)
   {
       $articleArray = ArticleRepository::getList($where);
       $user_ids = $this->selectFields('id','title','created_at','created_by')
            ->fromLocal($articleArray,'articles')
            ->pluck('created_by');
       $users = UserRepository::getUsersWithIds($user_ids);
       $this->innerJoinForeign($users,'userss')
            ->onRelationByObject(Relation::of()
                ->localAlias('articles')
                ->localField('created_by')
                ->foreignAlias('users')
                ->foreighField('id')) 
            ->addFieldMappingByObject(FieldMapping::of()
                ->key('created_at')
                ->src('created_at')
                ->type(MappingTypeEnum::METHOD_NAME))
            ->addFieldMappingByObject(FieldMapping::of()
                ->key('created_by')
                ->src('user_name')
                ->type(MappingTypeEnum::JOIN_FIELD));         
       return $this->toPaginateArray(PaginateTypeEnum::PAGINATE_API);
   }
}

``` 
  
Then you can call from controller:
  
```php
    return ArticlePresenter::of()->getArticleList($where);    
```

And you also could define the mapping in the property:

```php
    protected $list_mapping = [
         ['created_at' => ['src'=>'created_at', 'type'=>MappingTypeEnum::METHOD_NAME  ]], 
         ['created_by' => ['src'=>'user_name', 'type'=>MappingTypeEnum::JOIN_FIELD  ]],
    ];
    
    // You need to call:
    $this->addFieldMappingList($this->list_mapping);
    
```

Then the created_by will return the user_name field from the array Users.

Maybe you found that is very simple. 

Just all!

### Document

#### Class Presenter

##### Presenter::selectFields($field_lists)
   
* param $field_lists   
   
The $field_lists could be array or comma seperated string. 
   
With the function you could decide that which fields will be show.
   
And you could change the key name with the field mapping.

##### Presenter::fromLocal($data_list,$alias,$path)

* param $data_list 
  
The $data_list could be array, also could be Collection object of Laravel.

* param $alisa 
   
Optional paramater. The $alias is a name for we access it after.

* param $path 
  
Optional paramater. When the data is from elasticsearch, we should point that which node is fact data we need.
   
$path is a dot seperated string. 

##### Presenter::fromLocalItem($data_item,$alias,$data_path)

The function is same as the fucntion fromLocal. But it only for single item. So the first argument is $data_item and not $data_list.


##### Presenter::pluck(...$fields)

Get the foreign keys as array. After you could query the join array with the foreign keys.

* param $fields

After you got the foreign data, you could using "innerJoinForeign" and "outerJoinForeign" to make the join with foreign array.

If foreign keys are join same foreign array, you could pass the all local keys and pluck once. For instance:

Both the fields created_by and updated_by are in the table User. So you could:

```php
    $this->pluck('created_by','updated_by')
```

##### Presenter::innerJoinForeign($data_list,$alias,$keyed_by)

For the params of $data_list and $alias, see： fromLocal()

* param $keyed_by

The $keyed_by is the field name of foreign key in join array.

We sort the array using the $keyed_by for reducing the performance.
   
The function with join type "inner", that like database, if foreign array not exists, we will remove the record.
    
##### Presenter::outerJoinForeign($data_list,$alias,$keyed_by)       

The Outer join is mean that when the foreign record not exists, we will keep the record in the output table.

About paramerters, see innerJoinForeign

##### Presenter::onRelation($local_field, $foreign_alias, $foreign_field, $relation_type)

The function is like the on phrase "on" in the SQL.

All of the properties are: $local_alias, $local_field, $foreign_alias, $foreign_field, $relation_type
 $foreign_fieds
You need not pass $local_alias, because the program could get the $local_alias from other function.

* param $local_field

In SQL clause: on local_table.field_local_name = foreign_table.field_foreign_name. And the $local_field just is 'field_local_name'.

* param $foreign_alias
 
In SQL clause: on local_table.field_local_name = foreign_table.field_foreign_name. And the $foreign_alias is the 'foreign_table'.
  
* param $foreign_field
 
In SQL clause: on local_table.field_local_name = foreign_table.field_foreign_name. And the $foreign_field is the 'field_foreign_name'.
 
* param $relation_type 

The $relation_type is number of RelationEnum which contains values:
  
    -- HAS_ONE:   There is 1 item in join array.
    -- HAS_MANY:   There are many items in join array.
    -- HAS_MANY_MERGE: There are many items in join array and must to merge to a field.
    -- HAS_MANY_SPLIT: There are many items in join array and must to merge to a field.
    
For HAS_MANY_MERGE AND HAS_MANY_SPLIT you should give a function to merge the records.

##### Presenter::onRelationbyObject(Relation $relation)
  
The function is same as onRelation. But you should pass an object of Relation.

see Class Relation.

##### Presenter::addFieldMapping($key,$src,$type,$alias)

What is mapping? The mapping is used for the field transform.

* param $key 

The unique name for the mapping. And it also is the output fieldname. 

* param $src 

The $src is given the location where we could get the value. Maybe it is a name of a field or of a function, or of a formatter's name.

* param $type

The $type is given that how we could get the value. It is a number of MappingTypeEnum which contains values: 
 
    -- FIELD_NAME ： Access the value via field name
    -- DATA_FORMATER：Transform the value via method of class DataFormatter.
    -- METHOD_NAME: Access the value via method name. You should add member method to the class. The difference of the DataFormatter, DataFormatter could using for many fields. The mapping key of the DataFormatter is both source fieldname and output field name, and the member method is only for one field, and  the key of member method is only output field name.   
    -- ARRAY_PATH: Access the value via path of array. for instance： "a.b.c". If the data is from ElasticSearch, Or the data contains the Json fields. And you need to flatten it. You could use ARRAY_PATH.
    -- JOIN_FIELD: Access the value from the join array
    
* param $alias 
    
    If the field is in the join array, you should to passing the $alias for the performance.
    
The rules of mapping are:
   
* FIELD_NAME

    -- $key: could be any string;
    -- $src: must be the name of source field;
    -- $type: FIELD_NAME;
    -- $alias: Must be ''.   
    
* DATA_FORMATER

    -- $key: must be the name of source field;
    -- $src: must be the name of formater function or the value of FormatterEnum;
    -- $type: DATA_FORMATER;
    -- $alias: Must be given if field in join array.
  
* METHOD_NAME
    
    -- $key: could be any string; if is the name of source field, you will get the $value argument, otherwise, you should call $this->getValue() 
    -- $src: must be the name of member method;
    -- $type: METHOD_NAME;
    -- $alias: Must be given if field in join array.
    
* ARRAY_PATH
    
    -- $key: could be any string;
    -- $src: must be the path of the json field; And the root path node must be name of the field.
    -- $type: ARRAY_PATH;
    -- $alias: Must be given if field in join array.
   
* JOIN_FIELD 

    -- $key: could be any string;
    -- $src: must be the name of source field;
    -- $type: FIELD_NAME;
    -- $alias: Must be the alias of the join array.   
    
##### Presenter::addFieldMappingByObject(FieldMapping $mapping)
   
The function is same as addFieldMapping. But you should pass an object of FieldMapping.

see Class FieldMapping.
    
##### Presenter::addFieldMappingList($mapping_list)   

The function is same as addFieldMapping. But addFieldMapping only support to add 1 mapping.  addFieldMappingList support to add an array of mappings.

The array formatter is: 

```php

       [
            ['key1' => ['src'=>a, 'type'=>b， 'alias'=c  ]],
            ['key1' => ['src'=>a, 'type'=>b   'alias'=c  ]],
       ]
     
```

##### Presenter::addFormatter($name,$callback)   

Add a function to the DataFormatter. 

Please see the source of \Bardoqi\Sight\Formatters\DataFormatter. There are some functions for converting in the class.

And if you want to add new function, you could use addFormatter;

* param $name
   
The name of the Formatter. 

* $callback

The function of Converting.

##### Presenter::toArray()
    
Return the record list array.

##### Presenter::toItemArray()

Return on record. It is using with SelectLocalItem.

##### Presenter::toPaginateArray()

Return the record list array.

* param $paginate_type
if the value is PaginateTypeEnum::PAGINATE_API, is will return the data without link.
else return the data with link.

##### Presenter::toTreeArray($parent_id_key)

$return the list with tree structure.
  
* param $parent_id_key

Give the field name of the parent_id. The default value is 'parent_d'.

##### Presenter::getValue($field_name)

Get the value of the current item via name of field ($field_name) through the mapping.
  
##### Functions for API Response

There are the functions for the API response:

setError(), getError(), setMessage(), getMessage(), setStatusCode(), getStatusCode()


#### Class FieldMapping

##### FieldMapping::of()

It is a short-name function for create a FieldMapping instance.

And other functions is for the peroperty's value. 

You could use the operator chain for the instance initial.
  
For instance:
```php
    $mapping  = FieldMapping::of()
                ->key('created_at')
                ->src('created_at')
                ->type(MappingTypeEnum::METHOD_NAME);
```

#### Class Relation

##### Relation::of()

It is a short-name function for create a Relation instance.

And other functions are for the property's value. 

You could use the operator chain for the instance initial.
  
For instance:

```php
    $relation = Relation::of()
                ->localAlias('articles')
                ->localField('created_by')
                ->foreignAlias('users')
                ->foreighField('id')) ;
```

#### Class CombineItem
   
if you using the function $this->getCurrentItem(), it will be return a instance of CombineItem;
     
##### CombineItem::getItemValue($field_name,$offset = 0, $alias = null)

Get the value of an item. 

* param $field_name

The name of the field you want to read

* $offset

If the relation type is HAS_MANY, HAS_MANY_MERGE and HAS_MANY_SPLIT, you should pass the $offset.

* $alias
  
If the field you want to read is in join array, you should pass the $alias.

##### CombineItem::getData($alias)

Get the join data with an alias of join array. It will return an instance of IMapItem.

#### Interface IMapItem

There are 2 class implements the interface IMapItem: MultiMapItem, SingleMapitem.

##### IMapItem::findByPath($path,$offset = null)

Find the value form the json field. 

If the class is MultiMapItem, you should pass $offset.

##### IMapItem::hasColunm($name)

Check if the field name $name exists.

##### IMapItem::getItemValue($key,$offset = null);

Get the value form the field. 

If the class is MultiMapItem, you should pass $offset.
 
#### Exceptions
  
There are many exceptions for helping your coding and to flatten your developing.

We are enjoying to tell you the detail about: 

* Message: 'KeyedBy can not be empty!'

  -- For reduce the performance, we should make the join array keyed. So you must give the KeyedBy argument.

* Message: 'The KeyedBy '.$name.' is not correct!'

  -- The program has discovered that the KeyedBy is not a field name of the join array.

* Message: 'Alias Can Not Be Empty!'

  -- We access the values by alias, so it can not be empty.

* Message: 'Mapping Key Can Not Be Empty!'

  -- We use the mapping by key. So you should give a string key.
  
* Message: 'Mapping Source Can Not Be Empty!'
   
  --The src property is used for tell us where we could get the value. 
   
* Message: 'Mapping Type Is Not Valid!'

  --To avoid the error, you could to use MappingTypeEnum. 

* Message: 'Field Mapping List Not Found!'

  --Maybe you had given an empty mapping array.

* Message: 'Mapping Array Is Not Valid!'

  -- The format of Mapping array is like: 
  
```php
       [
            ['key1' => ['src'=>a, 'type'=>b， 'alias'=c  ]],
            ['key1' => ['src'=>a, 'type'=>b   'alias'=c  ]],
       ]
```  

* Message: "Field Or Mapping " .$name. " Not Found!"

  --We transform the data with field name, mapping key name. That is one of the name given by SelectFields is not found in the fields of arrays and the keys of mapping.

* Message: "Function ". $name" Exists Already!"

  --When you call the function AddFormatter, the same name function(formatter) exists already.

* Message: "Item Is Not Json String!"

  --When the findByPath would be called, the designated value should be a json string, but not.
     
### FAQ
   
Q; Could you tell me the difference about league/fractal And Sight?
   
A: The league/fractal is a best library for transforming the data. And with Sight you could using array join, 
   
Q: Why we need define the mapping? Why not using data structure like league/fractal?
  
A: With mapping we could to get manyfold using of functions and formatters for transform.

   

### Troubleshootings

* You can not use "where" to filter the array.
* You need to learn the format of the array paramaters.
* There is no any features for transform from view or API layer to data layer.
* Not support nested join.
* Only support equal relation.
* Others will be found by you. 

### Road Mapping
  
In the plan:
  
* 0.1.6 Release: Finish the artisan generator. 
* 0.1.5 Release: Publish the detail document.

Has released:
  
* 0.1.4 Release: Finish the testing of HAS_MANY and HAS_MANY_MERGE
* 0.1.3 Release: Finish the testing of HAS_ONE and HAS_MANY_SPILT. It is a 1st publish stable.
* 0.1.2 RC: Add HAS_MANY_SPILT relation. 
* 0.1.1 RC: Finished the HAS_ONE relation. 
* 0.1.0 RC: 1st draft.  

#### Contribution

1.  Fork the repository
2.  Create Feat_xxx branch
3.  Commit your code
4.  Create Pull Request
  
### Donation
  
If you find this project useful, you can buy author a glass of juice 🍹
    
![donate](https://raw.githubusercontent.com/BardoQi/bmc/master/myqr_en_sm.png)     

### License
  
MIT

Copyright [2020] Bardo Qi [bardoqi@gmail.com](bardoqi@gmail.com)



