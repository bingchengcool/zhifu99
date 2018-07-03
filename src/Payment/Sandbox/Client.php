<?php

/*
 * This file is part of the tuowt/Zhifu99\.
 *

 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Zhifu99\Payment\Sandbox;

use Zhifu99\Kernel\Traits\InteractsWithCache;
use Zhifu99\Payment\Kernel\BaseClient;
use Zhifu99\Payment\Kernel\Exceptions\SandboxException;

/**
 * Class Client.
 *
 * 
 */
class Client extends BaseClient
{
    use InteractsWithCache;

    /**
     * @return string
     *
     * @throws \Zhifu99\Kernel\Exceptions\InvalidConfigException
     * @throws \Zhifu99\Payment\Kernel\Exceptions\SandboxException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getKey(): string
    {
        if ($cache = $this->getCache()->get($this->getCacheKey())) {
            return $cache;
        }

        $response = $this->request('sandboxnew/pay/getsignkey');

        if ('SUCCESS' === $response['return_code']) {
            $this->getCache()->set($this->getCacheKey(), $key = $response['sandbox_signkey'], 24 * 3600);

            return $key;
        }

        throw new SandboxException($response['return_msg']);
    }

    /**
     * @return string
     */
    protected function getCacheKey(): string
    {
        return 'easywechat.payment.sandbox.'.md5($this->app['config']->app_id.$this->app['config']['mch_id']);
    }
}
