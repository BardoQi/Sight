# Sight
## 你所渴望的Laravel的Presnter层
 
[![Build Status](https://travis-ci.org/BardoQi/Sight.png?branch=master)](https://travis-ci.org/BardoQi/Sight)
[![Latest Stable Version](https://poser.pugx.org/bardoqi/sight/v)](//packagist.org/packages/bardoqi/sight) [![Total Downloads](https://poser.pugx.org/bardoqi/sight/downloads)](//packagist.org/packages/bardoqi/sight) [![Latest Unstable Version](https://poser.pugx.org/bardoqi/sight/v/unstable)](//packagist.org/packages/bardoqi/sight) [![License](https://poser.pugx.org/bardoqi/sight/license)](//packagist.org/packages/bardoqi/sight)

### 关于Sight
   
[English(英文)](https://github.com/BardoQi/Sight/blob/master/README.md)   
   
以前，在Laravel框架中没有MVP (Model View Presenter) 模式。 也许，你也知道，服务器端是没有Presenter层的。但我得告诉你，Sight就是一个。

Sight不只是把web应用转换为MVP模式，同时也是API应用MVP模式最好的代码库。现在，你可以使用Sight，它拥有优雅的架构，从而给你优雅的MVP模式。

MVC与MVP不同点在于，对于模形层，视图是完全被动且未知的。但在MVC中却不是这样。（当然，多层结构，即中间的Repository,Service可能不是这样），真正的MVC模式中，view也应当不用实现构造函数。

一个典型的MVP由以下几个部分组成：

* 数据访问层 (DataMappers, ORM 等)
* 业务逻辑层 (比如，验证，计算)
* 被动的视图类 (可以是模板，但最好是放在一个类中)
* Presenter桥接模型与视图。 

Sight 就是用于桥接业务逻辑层与视图层或控制器层(在API应用中) 。 Sight是一个 Mapping类，帮助你将源于数据库的数据转换为客户端可显示的数据。

Sight 是一个 Laravel 框架的Presenter层。
     
我们相信，当你开始使用Sight时，你将会更加快乐地写代码。  
     
使用Sight，你不再需要重复很多烦人的代码。

使用Sight，你不再需要搜索数组，因为Sight使用Mapping把join的数组都转换了。

使用Sight，你不再因为一个字段去join太多的表。

使用Sight，你可以停掉为查询表而且太多的"for" 或 "foreach".

使用Sight，大量的不必要的join查询将会避免。

现在就开始享受Sight吧！

### Sight做什么？
  
从数据层到视图层或API层，你一般需要写太多的foreach用于转换数据。

现在你不再需要这些foreach了，只要一个"ToArray()"就够了。

也许，它的缺陷则是，你可能以后都不会用foreach了! :)
   
### 为什么要用Sight?

也许你的团队成员经常在foreach中使用数据库查询。当你开始使用Sight时，这样的坏代码就会消失。
  
我们常常应当避免太多的join查询。（一些大厂联表不准超过3个）如果join只是为了一个字段，我们应当分开查询。但是，如果这么做，代码会增多。初学者可能还会重复在数组中Search。现在有了Sight,一切都简单了。
     
也许你也常常使用数据库的view去查询去转换很多的ID字段到名称。对于数据库性参，以及代码的可读性，这是一个不好的方式。使用Sight，则可以阻止这些查询的使用。
  
在数据库中，你可以使用hasOne、hasMany查询。但你没办法把一对多的合并到一条记录中。你可能要查询多次，并得手工合并。现在有了Sight,一切都简单了。

也许你曾用过像 League\Fractal 这样的库。（它确实优秀）不过，推荐你还是试一下Sight！

也许你在使用Laravel模型中的mutators。但有时，你可能要在做完什么以后再转换，mutators就不够灵活了。使用Sight，你可以在返回到控制器之前完成它。

### 主要功能与特性

* 字段转换。比如，int转为date, time, datetime ...
* 外键ID转换，比如通过array join将 user_id转换为user_name。
* 单一数组转换，以及通过array join的多数组转换
* 一对一join，比如article中的image_id到images中的任一字段，比如image_url，要以简单拿到
* 多对一join，比如 article中的image_ids数据有像'3,5,7,9'，(这当然是不太好的设计，但它确实可能节省表。)，你也可以轻松把图像数据合并到字段中。
* 简单的pluck，帮助你获取到用到的外键。
* 通过selectFields函数的参数传递字段列表，可以简单隐藏或打开列。
* 字段转换的mapping配置可以配置为转换器函数，数组的PATH以及方法名。
* 同时支持数据库(像MYSQL)和ElasticSearch中查到的数据。
* 支持直接静态调用。
* 支持扁平化来源于mysql和ElasticSearch的JSON数据。
* 支持inner join 和 outer join.
* 高性能，因为每一个数组最多循环两次。

### 开始使用

#### 安装
 
```php
$ composer require bardoqi/sight
```
#### 代码示例

非常简单，你就像使用MODEL那样。函数链可能像这样：

```php
    
    $this->selectFields()
        ->fromLocal()
        ->innerJoinForeign()
        ->onRelation()
        ->setMapping()
        ->toArray();

```

就像模型或者SQL:

```php
    $this->select()
        ->from()
        ->join()
        ->on()
        ->toArray();
        
        
```

当然，肯定不是完全一样！

首先，你要创建一个presenter类继承Sight/Presenter,例如以下的代码：
 
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

接下来，你可以添加函数获取数据了。以下例子，我们从Repositories中获取数据：

```php
namespace App\Presenter

use Bardoqi\Sight\Presenter;
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
       $this->innerJoinForeign($users,'userss')
            ->onRelationByObject(Relation::of()
                ->localAlias('articles')
                ->localField('created_by')
                ->foreignAlias('users')
                ->foreighField('id')) 
            ->addFieldMappingWithObject(FieldMapping::of()
                ->key('created_at')
                ->src('created_at')
                ->type(MappingTypeEnum::METHOD_NAME))
            ->addFieldMappingWithObject(FieldMapping::of()
                ->key('created_by')
                ->src('created_by')
                ->type(MappingTypeEnum::METHOD_NAME));         
       return $this->toPaginateArray(PaginateTypeEnum::PAGINATE_API);
   }
   
}

``` 
  
接下来，你就可以从控制器中调用：
  
```php
    return ArticlePresenter::getArticleList($where);    
```

并且你也可以定义Mapping:

```php
    protected $list_mappint = [
         ['created_at' => ['src'=>'created_at', 'type'=>MappingTypeEnum::METHOD_NAME  ]], 
         ['created_by' => ['src'=>'user_name', 'type'=>MappingTypeEnum::METHOD_NAME  ]],
    ];
    
    // 你只要这样调用:
    $this->addFieldMappingList($this->list_mapping);
```

这时，created_by返回的将是Users数据组中的user_name数值。

也许你会说，太简单了。是的！就这样。
 

### 文档

#### Class Presenter

##### Presenter::selectFields($field_lists)
   
* param $field_lists   
   
参数 $field_lists 可以是数组，也可以是逗号分隔的字符串。
   
由此，你可以选择哪些字段显示，并且，通过改变field mapping的Key实现改变输出的字段名。

##### Presenter::fromLocal($data_list,$alias,$path)

* param $data_list 
  
参数 $data_list 可以是数组，也可以是Laravel的集合Collection。

* param $alisa 
   
数组别名，可选参数。后续程序用它来访问。

* param $path 
  
可选参数。当使用ElasticSearch,我们无需手工抽出数据，给定数据$path即可。
   
$path是以点分隔的字符串。

##### Presenter::fromLocalItem($data_item,$alias,$data_path)

此函数与fromLocal功能相同，只不过它是针对单一记录item。所以，第一个参数是$data_item，而不是$data_list，

##### Presenter::pluck(...$fields)

从数组主表中获取外键的数组。一般是外联表数组中的ID。借此，你可以通过像findWithIds函数查出结果。

* param $fields

参数$fields可以是一个，也可以是多个，比如，如果是相同的外联表，就可以一次性传入，以使一次读出来。

获取到外联表数据后，你就可用“innerJoinForeign”和“outerJoinForeign”进行JOIN操作.
  
pluck对应的字段可是以逗号分隔的IP。虽说它不是好的数据结构，但它省表，外包常见。所以，支持。 

如果多个外键是联接到同一表，则可以一次传入。例如：

created_by and updated_by 字段均源于 User. 因而你可以:

```php
    $this->pluck('created_by','updated_by')
```
    

##### Presenter::innerJoinForeign($data_list,$alias,$keyed_by)

关于 $data_list 和 $alias 参数, 参见： fromLocal()

* param $keyed_by

参数 $keyed_by 是 join array的关联键的这字段名，可能是本地主键，或其它字段.

我们将数组转换为以$keyed_by为Key的结构以提升性能。
   
Join类型是"inner",与数据库相同, 如果外联表无记录, 此记录将被移除。
    
##### Presenter::outerJoinForeign($data_list,$alias,$keyed_by)       

Join类型是"outer",与数据库相同, 如果外联表无记录, 此记录仍被保留。

关于参数，参见 innerJoinForeign

##### Presenter::onRelation($local_field, $foreign_alias, $foreign_field, $relation_type)

此函数与SQL中的 "on" 类似。

全部属性是：$local_alias, $local_field, $foreign_alias, $foreign_field, $relation_type, $foreign_fieds
 
你不需要传入 $local_alias，程序可以获取到。
 
* param $local_field

如果用SQL语旬来说明，则是：on local_table.field_local_name = foreign_table.field_foreign_name
这里就是：on $local_alias.$local_field = $foreign_alias.$foreign_field

* param $foreign_alias
 
如果用SQL语旬来说明，则是：on local_table.field_local_name = foreign_table.field_foreign_name
这里就是：on $local_alias.$local_field = $foreign_alias.$foreign_field
 
* param $foreign_field
 
如果用SQL语旬来说明，则是：on local_table.field_local_name = foreign_table.field_foreign_name
这里就是：on $local_alias.$local_field = $foreign_alias.$foreign_field
 
* param $relation_type 

参数 $relation_type是RelationEnum枚举类型，有以下数值:
  
    -- HAS_ONE: join array中只有一条记录。
    -- HAS_MANY: join array中有多条记录。
    -- HAS_MANY_MERGE: join array中有多条记录，且要合并到一个字段中
    -- HAS_MANY_SPLIT: join array中有多条记录，源于主数组中有这样的结构"3,4,5",即，逗号分隔的IP。
    
对于 HAS_MANY_MERGE 和 HAS_MANY_SPLIT 你要在你的类中实现对应的函数。

##### Presenter::onRelationbyObject(Relation $relation)
  
此函数与onRelation相同，但传入的参数是Relation对象。

参见： Class Relation.

##### Presenter::addFieldMapping($key,$src,$type,$alias)

mapping是什么？用来转换字段的。

* param $key 

mapping的唯一名称，也与输出的字段名相同。

* param $src 

数据来源，一般是字段名，只有格式化时，是格式化函数名。

* param $type

$type告诉程序如何转换，它是MappingTypeEnum枚举类型，有以下数值：
 
    -- FIELD_NAME：直接以字段名读取数据。（一般情况下，这是不需要配的，因为，这是默认来源。）
    -- DATA_FORMATER：使用DataFormatter类中的转换函数。
    -- METHOD_NAME: 方法名， 您应该将成员方法添加到类中。 与DataFormatter的不同之处在于，DataFormatter可以用于许多字段。 DataFormatter的映射键既是源字段名又是输出字段名，成员方法仅用于一个字段，成员方法的键仅是输出字段名。 
    -- ARRAY_PATH: 获取数值是通过数组的PATH。例如： "a.b.c"。假如数据源于ElasticSearch，或者数据中含有JSON字段，且你需要扁平化时。你则要使用 ARRAY_PATH.
    -- JOIN_FIELD: 从关联表中读取字段。（一般情况下，这也是不需要配的，因为，这也是默认来源。）
    
* param $alias 
    
    如果数据确实是源于关联数组，那你最好配上别名，以提升性能。
    
Mapping的规则如下：
   
* FIELD_NAME

    -- $key: 可以为任意字符串。（输出字段名，如果与源字段名相同，不需要配置）
    -- $src: 必须是源字段名;
    -- $type: FIELD_NAME;
    -- $alias: 必须为空.   
    
* DATA_FORMATER

    -- $key: 必须是源字段名，（所以，如果源字段名不是$key时，只能用函数（方法））
    -- $src: 必须是格式化函数的名字，或者是FormatterEnum的枚举值;
    -- $type: DATA_FORMATER;
    -- $alias: 如果数值在关联表中，那你最好配上别名，以提升性能。
  
* METHOD_NAME
    
    -- $key: 可以为任意字符串; 如果是源字段名，那方法的$value参数会给你数值。否则，你得自己去调用：$this->getValue() 
    -- $src: 必须是被调的（由你实现的）成员方法名;
    -- $type: METHOD_NAME;
    -- $alias: 当$key是源字段名时，如果数值在关联表中，那你最好配上别名，以提升性能。
    
* ARRAY_PATH
    
    -- $key: 可以为任意字符串，可以定义你的输出字段名，比如PATH的叶节点;
    -- $src: 必须是JSON字段的PATH（以点分隔），根节点必须是字段名。
    -- $type: ARRAY_PATH;
    -- $alias: 如果数值在关联表中，那你最好配上别名，以提升性能。
   
* JOIN_FIELD 

    -- $key: 可以为任意字符串，可以定义你的输出字段名;
    -- $src: 必须是源字段名。
    -- $type: FIELD_NAME;
    -- $alias: 如果数值在关联表中，那你最好配上别名，以提升性能。
    
##### Presenter::addFieldMappingByObject(FieldMapping $mapping)
   
函数功能与 addFieldMapping 相同，但所用的是FieldMapping对象。（可以用FieldMapping::of()创建并初始化！）

参见：FieldMapping.
    
##### Presenter::addFieldMappingList($mapping_list)   

与函数 addFieldMapping 功能相同。但 addFieldMapping 一次只能加一条。 ddFieldMappingList可以让你添加一个数组。

数组的格式是 :

```php

       [
            ['key1' => ['src'=>a, 'type'=>b， 'alias'=c  ]],
            ['key1' => ['src'=>a, 'type'=>b   'alias'=c  ]],
       ]
     
```

##### Presenter::addFormatter($name,$callback)   

添加格式化函数。（因为，Presenter本身没有格式化功能，所以，要用此函数把格式化函数添加到DataFormatter中。

主请参考源码：\Bardoqi\Sight\Formatters\DataFormatter. 此类中有部分转换函数。

所以，如果你想增加新的，可以用此函数

* param $name
   
格式化器的名称。

* $callback

用于转换的函数。

##### Presenter::toArray()
    
最终返回数组的函数

##### Presenter::toItemArray()

返回一条记录，与SelectLocalItem配套使用，处理单条记录。

##### Presenter::toPaginateArray()

返回带翻页的数组。

* param $paginate_type

参数是枚兴值，如果是 PaginateTypeEnum::PAGINATE_API,  则没有页面链接的超链。否则会返回。

##### Presenter::toTreeArray($parent_id_key)

以树结构返回数据.
  
* param $parent_id_key

给出所用的parent_d字段名， 默认是'parent_d'.

##### Presenter::getValue($field_name)

通过 mapping 获取当前的($field_name) 的数值。
  
##### Functions For Api Response

其它用于API返回的函数：

setError(),getError(),setMessage(),getMessage(),setStatusCode(),getStatusCode()


#### Class FieldMapping

##### FieldMapping::of()

短名函数用来创建FieldMapping实例.

其它的函数则是用来操作属性。

初始化时，你可以用链式操作，例如：
 
```php
    $mapping  = FieldMapping::of()
                ->key('created_at')
                ->src('created_at')
                ->type(MappingTypeEnum::METHOD_NAME);
```

#### Class Relation

##### Relation::of()

短名函数用来创建Relation实例.

其它的函数则是用来操作属性。

初始化时，你可以用链式操作，例如：

```php
    $relation = Relation::of()
                ->localAlias('articles')
                ->localField('created_by')
                ->foreignAlias('users')
                ->foreighField('id')) ;
```

#### Class CombineItem

如果你使用$this->getCurrentItem()函灵敏则会返回CombineItem的实例。
 
与$this->getValue()不同，你可以操作原始数据。前者是通过Mapping获得数据。
   
     
##### CombineItem::getItemValue($field_name,$offset = 0, $alias = null)

获得数据项的值（原始数据）。

* param $field_name

此参数是你想读的字段名。

* $offset

在HAS_MANY、 HAS_MANY_MERGE、HAS_MANY_SPLIT关系中，因为会有多条记录，所以，需要指定$offset。

* $alias
  
如果你想读的是JOIN数组中的数据，为了性能，最好指定$alias。  

##### CombineItem::getData($alias)

通过alias把JOIN数组中的数据一次性读出来。将会返回接口为IMapItem的实例。这对定义HAS_MANY_MERGE、HAS_MANY_SPLIT的转换函数有用。

#### Interface IMapItem

有两个类实现了IMapItem接口：MultiMapItem, SingleMapitem。

##### IMapItem::findByPath($path,$offset = null)

此函数是提供给MappingTypeEnum::ARRAY_PATH所用有。用来查找JSON字段中的数据。

如果实例是MultiMapItem，你必须要传$offset。

##### IMapItem::hasColunm($name)

检查数据中字段名为$name的是否存在。

##### IMapItem::getItemValue($column_name,$offset = null);

传入列名，获取此列的数据。如果实例是MultiMapItem，你必须要传$offset。

如果你想获取Mapping转换后的数据，请用$this->getValue()
 
#### Exceptions
  
为了使您开发变得简单，我们定义了很多异常。我们很乐意告诉您这些异常的详情。


* Message: 'KeyedBy can not be empty!'

  -- 程序是通过关联字段为KEY后，进行联表的。所以，你必须要传JOIN数组的KeyedBy。

* Message: 'The KeyedBy '.$name.' is not correct!'

  -- 程序发现你传的KeyedBy不是JOIN数组的字段名。

* Message: 'Alias Can Not Be Empty!'

  -- 在调用innnerJoinforeign()或outerJoinforeign()时未传alias参数

* Message: 'Mapping Key Can Not Be Empty!'

  -- 未指定Mapping的唯一名称Key。
  
* Message: 'Mapping Source Can Not Be Empty!'
   
  --未指定Mapping的src.
   
* Message: 'Mapping Type Is Not Valid!'

  --是避免此错，你可以使用MappingTypeEnum
   
* Message: 'Field Mapping List Not Found!'

  --你指定的Mapping数组是空的。

* Message: 'Mapping Array Is Not Valid!'

  --Mapping数组非法，请参照以下格式：
  
```php
       [
            ['key1' => ['src'=>a, 'type'=>b， 'alias'=c  ]],
            ['key1' => ['src'=>a, 'type'=>b   'alias'=c  ]],
       ]
```  

* Message: "Field Or Mapping " .$name. " Not Found!"

  --所有你在selectFields()传入的field name, 必须是你给的数组中的字段名，或者是类的方法名，或为格式化函数的名称。但程序发现，都不是。（这是一个MAPPING有效性验证，为了效率，生产环境是不运行的。）
   
* Message: "Function ". $name ." Exists Already!"

  --当你使用AddFormatter添加格式化函数时，程序发现，同名的格式化函数已存在。

* Message: "Item Is Not Json String!"

  --当findByPath被调用时，目标字段的数据必须是json字串，但程序发现不是。
     
### 常见问题
   
Q; 你能告诉我关于 league/fractal 和 Sight 的区别吗?
   
A: league/fractal 是一个优秀的转换库。 Sight支持array join。
   
Q: 为什么要定义mapping? 为什么不像league/fractal用结构定义?
  
A: 使用mapping我们可以重用很多转换函数和格式化函数。
 

### 缺陷

* 没有Where过滤。
* 一些以数组为参数的格式需要学习。
* 关联条件只支持等于，没有不等于的条件。
* 还没有反向的，即从View,Api层转换到Data层的功能。
* 不支持嵌套JOIN。当然你可以用程序实现。
* 其它你将会发现的。

### 路线图

计划中:

* 0.1.7 Release: 添加JSON枚举。
* 0.1.6 Release: 完成artisan生成器。 
* 0.1.5 Release: 发布详细文档
* 0.1.4 Release: 完成HAS_MANY 和 HAS_MANY_MERGE部分的单元测试

已发布：
   
* 0.1.3 Release: 完成了HAS_ONE和HAS_MANY_SPILT的单元测试。此为第一个公开稳定版。
* 0.1.2 RC: 添加了HAS_MANY_SPILT关系 
* 0.1.1 RC: 完成了HAS_ONE关系。 
* 0.1.0 RC: 第一份草稿

#### 贡献

1.  Fork此源码库
2.  创建 Feat_xxx 分支
3.  提交你的代码
4.  创建 Pull Request

### 捐赠 

如果你觉得此项目对你有用，那请你帮助买杯果汁。🍹
   
![donate](https://raw.githubusercontent.com/BardoQi/bmc/master/myqr_ch_sm.png)    

### 授权
  
MIT

Copyright [2020] Bardo Qi [bardoqi@gmail.com](bardoqi@gmail.com)




