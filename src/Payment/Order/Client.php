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
    public function unify($params)
    {
        $params['appId'] = $this->app['config']->appId;
        if (empty($params['clientIp'])) {
            $params['clientIp'] = Support\get_client_ip();
        }
        $params['currency'] = $params['currency'] ? $params['currency']: $this->app['config']['currency'];
        
        $params['timeExpire'] = $params['expireDateTime'] ? $params['expireDateTime']: date('Y-m-d H:i:s', strtotime("+1 day"));

        $params['notifyUrl'] = $params['notifyUrl'] ? $params['notifyUrl']: $this->app['config']['notifyUrl'];

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
    public function queryByTradeNo($tradeNo)
    {
        return $this->query([
            'orderNO' => $tradeNo,
        ]);
    }

    /**
     * @param array $params
     *
     * @return \Psr\Http\Message\ResponseInterface|\Zhifu99\Kernel\Support\Collection|array|object|string
     *
     * @throws \Zhifu99\Kernel\Exceptions\InvalidConfigException
     */
    protected function query($params)
    {
        $params['appId'] = $this->app['config']->appId;

        return $this->request($this->wrap('query.ashx'), $params);
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
    public function close($tradeNo)
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
