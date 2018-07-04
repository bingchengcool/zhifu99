# zhifu99
网龙集团统一支付接口


## Usage

### 服务端发起支付请求:

```php
use Zhifu99\Factory;

$config = [
    // 必要配置
    'appId'     => 'xxxx',
    // APP key 密钥
    'key'       => '',
    // 支付的来源 默认其他（0-其他；1-iOS；2-android；3-PC；4-xxx）
    'paySource' => '0',
    // 三位 ISO 货币代码
    'currency'  => 'CNY',
    // 你也可以在下单时单独设置来想覆盖它
    'notifyUrl' => '默认的订单回调地址',
];

$app = Factory::payment($config);

// 发起支付请求
$result = $app->order->unify([
    'userName'       => '用户名',
    'userId'         => '1',
    'orderNO'        => 'O123456',
    'channel'        => 'wx_mini',
    'subject'        => '标题',
    'body'           => '腾讯充值中心-QQ会员充值',
    'expireDateTime' => date('Y-m-d H:i:s', strtotime("+1 day")),
    'amount'         => 88,
    // 支付结果通知网址，如果不设置则会使用配置里的默认地址
    'notifyUrl'      => 'https://pay.weixin.qq.com/wxpay/pay.action',
]);
```

### 服务器异步通知：
```php
$app = Factory::payment($config);

$response = $app->handlePaidNotify(function ($notify, $successful){
    
});

return $response;
```

### 订单查询：
#### 根据订单号查询：
```php
// 订单号查询
$app->order->queryByTradeNo($tradeNo, $channel);
```

#### 订单查询：
```php
$params = [
    'orderNO' => '',  // 必填
    'channel' => '',  // 必填
    'username' =>     // 可选
];

$app->order->query($params);
```

### 订单取消：
```php
$params = [
    'orderNO' => '',  // 必填
    'channel' => '',  // 必填
    'username' =>     // 必填
];

$app->order->close($params);
```
