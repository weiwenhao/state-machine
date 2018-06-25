
## 介绍

> 基于laravel model的状态管理扩展

开发中经常需要为实体定义一些状态
如对于文章实体可能会有 new/draft/deleted
对于支付实体可能会有 awaiting_payment/cancelled/paid/refunded
对于订单实体↓
![](http://omjq5ny0e.bkt.clouddn.com/15296547862738.jpg)


对于小程序的的迭代周期实体可能会有 ↓
![](http://omjq5ny0e.bkt.clouddn.com/15296548277088.jpg)





面对开发过程中繁杂的状态,我们需要一个状态管理工具.
而state-machine就是管理状态之间转换的一个laravel扩展,拥有优雅的语法与回调机制.


## 安装

    composer require weiwenhao/state-machine
    
> 支持laravel5.5+版本

## 创建graph

`php artisan make:graph TestGraph`

运行该命令将会在`app\Graphs`目录下创建一个TestGraph文件.
如果你不明白如何配置 graph,则可以运行
`php artisan make:graph TestGraph -demo` 来生成一个demo.

```php
<?php

namespace App\Graphs;

use Illuminate\Database\Eloquent\Model;
use Weiwenhao\StateMachine\Graph;

class TestGraph extends Graph
{
    /**
     * 所有可选的状态
     * @var array
     */
    protected $states = [
        'cart',
        'new',
        'cancelled',
        'fulfilled'
    ];

    /**
     * 初始状态
     * @var string
     */
    protected $initState = 'cart';

    /**
     * 定义状态之间的转换
     * @var array
     */
    protected $graph= [
        'create' => [
            'from' => ['cart'],
            'to' => 'new',
        ],
        'cancel' => [
            'from' => ['new'],
            'to' => 'cancel',
        ],
        'fulfilled' => [
            'from' => ['new'],
            'to' => 'fulfilled'
        ]
    ];

    /**
     * Model中用于进行状态转换的key 默认使用state字段
     * @var string
     */
    protected $key = 'state';

    /**
     * 转换发生时调用的回调(后置回调)
     * @param $object
     * @return string
     */
    public function onCreate(Model $object)
    {
        return 'created';
    }
}

```

其中对于state更推荐使用一个enum类型来处理,所以我通常会在laravel的app目录下创建一个Enums目录来存放枚举类型

```php
<?php

namespace App\Enums;

interface OrderState
{
    const NEW = 0;
    const CANCELLED = 1;
    const FULFILLED = 2;
}

```



## 使用

#### init graph
`$testGraph = new TestGraph($order)` 或 `$testGraph = TestGraph::with($order)`

#### init state
`TestGraph::with($order)->init()`

使用Graph中定义的initState对$order的state属性进行初始化

#### apply transition
`TestGraph::with($order)->apply('cancel')`

对$order的state属性应用cancel这个转换,如果不允许应用cancel则会抛出一个
StateMachineException

由于apply方法严格遵守TestGraph中$graph中定义的状态转换.如果存在可能会出现异常的apply(),并且想要处理失败后的结果则可以像下面这样处理

```
    use Weiwenhao\StateMachine\StateMachineException
    
    try {
        TestGraph::with($order)->apply('cancel')
    } cache (StateMachineException $e) {
        // handle...
    }
```

如果你觉得这样太过麻烦, 只需要在apply的时候传递第二个参数为`true`, 则对于不允许apply的情况则不会抛出异常, 而是返回一个false;

> state-machine只是进行了state的状态转换,不会去进行model的save操作

#### apply event
在apply transition后,state-machine 回去对应的Graph中查找是否存在对应的transition回调,有则调用, 并且将对应的model作为参数传递

如`TestGraph::with($order)->apply('cancel')`则会调用TestGraph下的onCancel

```
# TestGraph.php

public function onCancel($order)
{
    // cancel..
}

```

> laravel model的观察者模式已经是一种很优雅的事件处理机制了,可能有个同学会觉得两者存在冲突.
> 但是我认为并不会, model event专注于整个模型实体的增删改事件, 而state则是更加细颗粒的,更加专注于处理state的事件机制.

#### can transition
`TestGraph::with($order)->can('cancel')` 

返回一个bool值,在apply()的时候会先调用can进行检测

#### get state

`TestGraph::with($order)->getState()` 

在model中使用 $order->state 是一种更加方便的选择

#### get possible transitions

`TestGraph::with($order)->getPossibleTransitions()` 

返回一个array,包含当前状态下可以使用的transitions



## 其他可选方案

[winzou/state-machine](https://github.com/winzou/state-machine)


