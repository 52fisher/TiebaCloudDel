# TiebaCloudDel
a tool to delete unreasonable posts automatically
一个免除人工的云删帖，省去了天天守在贴吧面前等着删违规贴的烦恼.使用本工具，可以轻松对一些违规贴子进行删除。
如果使用不当，恶意清空吧内数据可能会被下吧主的哟。 

## 实现功能
1. 两种工作模式支持，CGI与CLI
2. 两种检测模式，同时支持关键词和正则表达式
3. 两种匹配方案，PC方案可以限制用户等级，但是效率较低；客户端方案检测快速、高效，小内存服务器福利（PC方案内测中）
4. 多个设置选项，比其他云删帖更加人性化，轻松自定义
5. 支持删帖违规并封禁当前用户，不给广告狗任何机会
### 历史更新

#### v4.0 诈尸更新
- **以前的文件请直接停止使用**
- 修复了API更新的BUG
- 修复日志异常的BUG
- 修复CLI模式下输出问题
- 修复封禁失效的重大BUG
- 代码重构，并针对CLI和云监控分别优化

#### v3.3
- 正式版统一移除自定义封禁、循环封禁等黑暗料理
- 调整api，解决部分异地被封禁问题
- 含有中文的正则无需在p键转码，如果一定要转码（部分字符存在兼容问题），请把**u**改为**x**并在该表达式后加上标识符**u** ，如匹配所有中文 **/^[x{4e00}-x{9fa5}]+$/u**

#### v3.2 
- **内测版本**
- 增加范围内自定义封禁天数功能
- 增加循环封禁
- 代码调整为面向过程
- 改善处理逻辑

#### v3.1
- CLI模式不写日志文件
- 增加网络拥堵/服务器网络不佳自动终止进程(CLI模式)
- ~~小吧封禁十天~~  过于危险不开放发布
- PC方案文件待调整，持续内测中
- CLI模式可关闭控制台，如需终止进程可以改变config.php中的内容，详细步骤见使用说明
#### v3.0 
- 实现两种工作模式，CGI与CLI互不干扰
- 两种匹配方案自由切换，PC方案新增用户等级限制（内测中）
- 增加BDUSS失效/无效提醒，失效时自动终止程序，不占用空间
#### v2.0
- 改进检测规则，增加正则表达式，添加 str键 两种模式自由控制
- 增加日志功能，每日删帖封禁一览无遗
- 代码重构
#### v1.0 
- 实现云删帖基本功能
- 封禁违规用户
- 关键词删除

## 参数说明
```php
以下为配置信息的参数说明,具体请修改$config里面的内容

以CLI模式运行，同时需设置INTERVAL参数。如果不理解，请勿修改。默认false
首次运行后程序 自动监控，无需挂云监控。常驻后台，小内存请用云监控代替

CLI      => true;
interval => 60 * ; //2min

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

```

## 使用方法
- 本程序可以运行在php 5.4 以上的环境，如果不是，建议自己修改源码实现兼容或者不用.
- CLI模式: 使用控制台切换到cron.php文件的工作目录，不会使用cmd的请百度.如你放在了C盘根目录的WEB文件夹下面
控制台输入： **php C:\\WEB\cron.php**
开始工作后，**控制台会输出当前程序删帖/封禁/巡逻时间记录，不写日志文件**.
- CGI模式: 使用云监控或者浏览器定时刷新保持会话，使用云监控的办法请自行百度.需要监控的文件为cron.php，在压缩文件的根目录.
**操作记录（删帖/封禁）会被写入到log文件夹下面的的lists-年-月-日.txt 文件里，如果BDUSS失效，会写入到ERROR-年-月-日.txt文件里.**

## 使用说明
- 使用CLI模式时，如果网络故障或者服务器无法访问，会自动终止当前任务
- 如果发现程序没有进行监控，请检查是否网络故障/服务器故障
- 如果操作日志里面删帖记录出现大量的失败记录，一般来说是当前账号删帖过多或者过于频繁，请暂停一段时间后再试
- 如果BDUSS失效/无效，本程序会主动终止当前任务(CLI模式直接停止并给出失效提醒)，直到重新获取有效的BDUSS.云监控则会直接生成一个stop文件在lib目录下面，重新配置好BDUSS后如果不删除此文件也不会工作.
- 如果开启CLI模式，但却同时使用云监控定时访问。可能会造成内存溢出等不可测现象，请谨慎使用
- 开启CLI模式后，控制台不可以关闭。
- CLI模式下，如果需要终止程序，请直接使用ctrl+C强行终止即可.云监控下如果需要终止，请停止云监控或者在lib目录下新建一个stop文件即可
