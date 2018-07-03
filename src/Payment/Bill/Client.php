<?php

/*
 * This file is part of the tuowt/Zhifu99\.
 *

 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Zhifu99\Payment\Bill;

use Zhifu99\Kernel\Http\StreamResponse;
use Zhifu99\Payment\Kernel\BaseClient;

class Client extends BaseClient
{
    /**
     * Download bill history as a table file.
     *
     * @param string $date
     * @param string $type
     *
     * @return \Zhifu99\Kernel\Http\StreamResponse|\Psr\Http\Message\ResponseInterface|\Zhifu99\Kernel\Support\Collection|array|object|string
     *
     * @throws \Zhifu99\Kernel\Exceptions\InvalidConfigException
     */
    public function get(string $date, string $type = 'ALL', array $optional = [])
    {
        $params = [
            'appid' => $this->app['config']->app_id,
            'bill_date' => $date,
            'bill_type' => $type,
        ] + $optional;

        $response = $this->requestRaw($this->wrap('pay/downloadbill'), $params);

        if (0 === strpos($response->getBody()->getContents(), '<xml>')) {
            return $this->castResponseToType($response, $this->app['config']->get('response_type'));
        }

        return StreamResponse::buildFromPsrResponse($response);
    }
}
