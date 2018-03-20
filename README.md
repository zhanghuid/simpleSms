### simpleSms - a package for send sms ![pass](https://travis-ci.org/zhanghuid/simpleSms.svg?branch=master)
>模仿[overtrue/easy-sms](https://github.com/overtrue/easy-sms)而做的一款极简版

### 环境需求
- PHP >= 5.6

### 使用

```php
// 使用模版方式
 $message1 = new Message([
      'template' => '{:date} is the good day',
      'data' => [
          '{:date}' => date('Y-m-d')
       ]
  ])
// 直接发送内容  
  $message => new Message([
        'content' => 'this is a test message',
  ]);
  
  $config = [
        'gateway' => [
                'qcloud' => [
                    'sdk_app_id' => 'xxxx',
                    'app_key' => 'xxxx',
                    'type' => 0
                ],
            ],
  ];
  
  $sms = new Sms($config);
  $sms->send(13800138000, $message1);
  $sms->send(13800138000, $message2);

```
### License
MIT

### 
> 如有问题，请联系本人**zhang.huid@qq.com**删除代码
