<?php

/*
 * This file is part of the tuowt/Zhifu99\.
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Zhifu99\Payment;

use Closure;
use Zhifu99\Kernel\ServiceContainer;
use Zhifu99\Kernel\Support;

/**
 * Class Application.
 *
 * @property \Zhifu99\Payment\Order\Client              $order  订单（发起申请，查询，取消）
 * @property \Zhifu99\Payment\Refund\Client             $refund 退款
 * @property \Zhifu99\Payment\Bill\Client               $bill   对账
 */
class Application extends ServiceContainer
{
    /**
     * @var array
     */
    protected $providers = [
        Bill\ServiceProvider::class,
        Order\ServiceProvider::class,
        Refund\ServiceProvider::class,
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
     * @param string|null $endpoint
     *
     * @return string
     */
    public function getKey($endpoint = null)
    {
        return $this['config']->key;
    }
}
