<?php

/*
 * This file is part of the tuowt/Zhifu99\.
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Zhifu99\Payment\Order;

use Zhifu99\Kernel\Support;
use Zhifu99\Payment\Kernel\BaseClient;

class Client extends BaseClient
{
    /**
     * Unify order.
     *
     * @param array $params
     *
     * @return \Psr\Http\Message\ResponseInterface|\Zhifu99\Kernel\Support\Collection|array|object|string
     *
     * @throws \Zhifu99\Kernel\Exceptions\InvalidConfigException
     */
    public function unify(array $params)
    {
        if (empty($params['clientIp'])) {
            $params['clientIp'] = Support\get_client_ip();
        }

        $params['appId'] = $this->app['config']->appId;
        $params['notifyUrl'] = $params['notifyUrl'] ?? $this->app['config']['notifyUrl'];

        return $this->request($this->wrap('create.ashx'), $params);
    }

    /**
     * Query order by out trade number.
     *
     * @param string $number
     *
     * @return \Psr\Http\Message\ResponseInterface|\Zhifu99\Kernel\Support\Collection|array|object|string
     *
     * @throws \Zhifu99\Kernel\Exceptions\InvalidConfigException
     */
    public function queryByOutTradeNumber(string $number)
    {
        return $this->query([
            'out_trade_no' => $number,
        ]);
    }

    /**
     * Query order by transaction id.
     *
     * @param string $transactionId
     *
     * @return \Psr\Http\Message\ResponseInterface|\Zhifu99\Kernel\Support\Collection|array|object|string
     *
     * @throws \Zhifu99\Kernel\Exceptions\InvalidConfigException
     */
    public function queryByTransactionId(string $transactionId)
    {
        return $this->query([
            'transaction_id' => $transactionId,
        ]);
    }

    /**
     * @param array $params
     *
     * @return \Psr\Http\Message\ResponseInterface|\Zhifu99\Kernel\Support\Collection|array|object|string
     *
     * @throws \Zhifu99\Kernel\Exceptions\InvalidConfigException
     */
    protected function query(array $params)
    {
        $params['appId'] = $this->app['config']->appId;

        return $this->request($this->wrap('pay/orderquery'), $params);
    }

    /**
     * Close order by out_trade_no.
     *
     * @param string $tradeNo
     *
     * @return \Psr\Http\Message\ResponseInterface|\Zhifu99\Kernel\Support\Collection|array|object|string
     *
     * @throws \Zhifu99\Kernel\Exceptions\InvalidConfigException
     */
    public function close(string $tradeNo)
    {
        $params = [
            'appId' => $this->app['config']->appId,
            'orderNO' => $tradeNo,
        ];

        if (empty($params['clientIp'])) {
            $params['clientIp'] = Support\get_client_ip();
        }

        return $this->request($this->wrap('closeorder.ashx'), $params);
    }
}
