<?php

/*
 * This file is part of the tuowt/Zhifu99\.
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Zhifu99\Payment;

use Closure;
use Zhifu99\BasicService;
use Zhifu99\Kernel\ServiceContainer;
use Zhifu99\Kernel\Support;

/**
 * Class Application.
 *
 * @property \Zhifu99\Payment\Order\Client              $order  订单（发起申请，查询，取消）
 * @property \Zhifu99\Payment\Refund\Client             $refund 退款
 * @property \Zhifu99\Payment\Bill\Client               $bill   对账
 * @property \Zhifu99\BasicService\Url\Client           $url
 * @property \Zhifu99\Payment\Security\Client           $security
 *
 * @method mixed pay(array $attributes)
 * @method mixed authCodeToOpenid(string $authCode)
 */
class Application extends ServiceContainer
{
    /**
     * @var array
     */
    protected $providers = [
        BasicService\Url\ServiceProvider::class,
        Base\ServiceProvider::class,
        Bill\ServiceProvider::class,
        Order\ServiceProvider::class,
        Refund\ServiceProvider::class,
        Security\ServiceProvider::class,
    ];

    /**
     * @var array
     */
    protected $defaultConfig = [
        'http' => [
            'base_uri' => 'http://zhifu.99.com/sdp/paysdk/chargev2/',
        ],
    ];

    /**
     * Build payment scheme for product.
     *
     * @param string $productId
     *
     * @return string
     */
    public function scheme(string $productId): string
    {
        $params = [
            'appid' => $this['config']->app_id,
            'mch_id' => $this['config']->mch_id,
            'time_stamp' => time(),
            'nonce_str' => uniqid(),
            'product_id' => $productId,
        ];

        $params['sign'] = Support\generate_sign($params, $this['config']->key);

        return 'weixin://wxpay/bizpayurl?'.http_build_query($params);
    }

    /**
     * @param \Closure $closure
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @codeCoverageIgnore
     *
     * @throws \Zhifu99\Kernel\Exceptions\Exception
     */
    public function handlePaidNotify(Closure $closure)
    {
        return (new Notify\Paid($this))->handle($closure);
    }

    /**
     * @param \Closure $closure
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @codeCoverageIgnore
     *
     * @throws \Zhifu99\Kernel\Exceptions\Exception
     */
    public function handleRefundedNotify(Closure $closure)
    {
        return (new Notify\Refunded($this))->handle($closure);
    }

    /**
     * @param \Closure $closure
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @codeCoverageIgnore
     *
     * @throws \Zhifu99\Kernel\Exceptions\Exception
     */
    public function handleScannedNotify(Closure $closure)
    {
        return (new Notify\Scanned($this))->handle($closure);
    }

    /**
     * @return bool
     */
    public function inSandbox(): bool
    {
        return (bool) $this['config']->get('sandbox');
    }

    /**
     * @param string|null $endpoint
     *
     * @return string
     */
    public function getKey(string $endpoint = null)
    {
        return $this['config']->key;
    }

    /**
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this['base'], $name], $arguments);
    }
}
