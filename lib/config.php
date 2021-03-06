<?php
/*
 * byfisher
 * 2018.3.7
 */
//支持两种工作模式，控制台与浏览器。
//使用方法：定时任务，在云监控/系统任务里面加入定时监控任务
/*监控时间：
中型吧(10w)时间建议在 30min
中大型吧(100w)  15min
大型贴吧200w  10min
超大型贴吧(500w) 5min
 */
/*
以下为配置信息的参数说明,具体请修改$config里面的内容

以CLI模式运行，同时需设置INTERVAL参数。如果不理解，请勿修改。默认false
CLI      => true;
interval => 60 * 2 ; //2min

具有管理身份的BDUSS，建议语音小编
bduss => ''

需要监控的吧名
kw => '吧名';

使用客户端接口，速度快效率更高，但无法限制发帖用户等级。默认开启，关闭改为false
注意：目前开发版只能使用客户端接口，此开关无效.
wap => (boolean) true;

封禁用户开关，默认不封禁，封禁改为true
block => (boolean) false;

封禁天数，小吧或者有权限的小编请设置为1，否则不会生效。
days => 1;

封禁理由，如果不想留理由直接写为''即可
reason => '违反吧规'

广告检测规则
ad 数组
检测模式： 关键词模式，屏蔽正则。默认开启，使用正则设为 fasle;
str => (boolean) true;

如果是正则模式，在 p键 中填入正则表达式，注意使用//将表达式包围起来。
如果有多个表达式，请用如下形式：
'p'   => ['/我.*?开车/',
'/打.*?钱/',
'/\d{5,11}/',
],
如果是关键词模式，请直接填写关键词。多个关键词请用如下形式：
'p'   => ['【视频】',
'有小哥哥要恋爱的嘛',
'下面好痒',
],

不要将匹配词设置太多，如果你的服务器不够强大。建议在15个以下

 */

// 请按照需求更改以下内容
$config = [
    'kw'       => "吧名",
    'CLI'      => true,
    'interval' => 300 * 2, //10 min
    'bduss'    => '你的BDUSS',
    'wap'      => true,
    'block'    => false,
    'days'     => 1,
    'reason'   => '抱歉，因您发布了违反吧规的内容暂时被吧务封禁！',
    'ad'       => [
        'str' => true,
        'p'   => [
            '【视频】',
            '有小哥哥要恋爱的嘛',
        ],
    ],
];
?>
