<?php

/*
 * This file is part of the tuowt/Zhifu99\.
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Zhifu99\Payment\Notify;

use Closure;
use Zhifu99\Kernel\Exceptions\Exception;
use Zhifu99\Kernel\Support;
use Zhifu99\Kernel\Support\XML;
use Zhifu99\Payment\Kernel\Exceptions\InvalidSignException;
use Symfony\Component\HttpFoundation\Response;

abstract class Handler {
    const SUCCESS = 'success';
    const FAIL = 'fail';

    /**
     * @var \Zhifu99\Payment\Application
     */
    protected $app;

    /**
     * @var array
     */
    protected $message;

    /**
     * @var string|null
     */
    protected $fail;

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * Check sign.
     * If failed, throws an exception.
     *
     * @var bool
     */
    protected $check = true;

    /**
     * Respond with sign.
     *
     * @var bool
     */
    protected $sign = false;

    /**
     * @param \Zhifu99\Payment\Application $app
     */
    public function __construct($app) {
        $this->app = $app;
    }

    /**
     * Handle incoming notify.
     *
     * @param \Closure $closure
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    abstract public function handle(Closure $closure);

    /**
     * @param string $message
     */
    public function fail($message) {
        $this->fail = $message;
    }

    /**
     * @param array $attributes
     * @param bool $sign
     *
     * @return $this
     */
    public function respondWith($attributes, $sign = false) {
        $this->attributes = $attributes;
        $this->sign = $sign;

        return $this;
    }

    /**
     * Build xml and return the response to WeChat.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse() {
        $base = [
            'return_code' => is_null($this->fail) ? static::SUCCESS : static::FAIL,
            'return_msg'  => $this->fail,
        ];

        $attributes = array_merge($base, $this->attributes);

        if ($this->sign) {
            $attributes['sign'] = Support\generate_sign($attributes, $this->app->getKey());
        }

        return new Response($attributes['return_code']);
    }

    /**
     * Return the notify message from request.
     *
     * @return array
     *
     * @throws \Zhifu99\Kernel\Exceptions\Exception
     */
    public function getMessage() {
        if (!empty($this->message)) {
            return $this->message;
        }

        $message = $this->app['request']->getContent();

        if (!is_array($message) || empty($message)) {
            throw new Exception('Invalid request.', 400);
        }

        if ($this->check) {
            $this->validate($message);
        }

        return $this->message = $message;
    }

    /**
     * Decrypt message.
     *
     * @param string $key
     *
     * @return string|null
     *
     * @throws \Zhifu99\Kernel\Exceptions\Exception
     */
    public function decryptMessage($key) {
        $message = $this->getMessage();
        if (empty($message[$key])) {
            return null;
        }

        return Support\AES::decrypt(
            base64_decode($message[$key], true), md5($this->app['config']->key), '', OPENSSL_RAW_DATA, 'AES-256-ECB'
        );
    }

    /**
     * Validate the request params.
     *
     * @param array $message
     *
     * @throws \Zhifu99\Payment\Kernel\Exceptions\InvalidSignException
     */
    protected function validate($message) {
        $sign = $message['sign'];
        unset($message['sign']);

        $message = Support\params_sort(get_class($this), $message);
        if (Support\generate_sign($message, $this->app->getKey()) !== $sign) {
            throw new InvalidSignException();
        }
    }

    /**
     * @param mixed $result
     */
    protected function strict($result) {
        if (true !== $result && is_null($this->fail)) {
            $this->fail(strval($result));
        }
    }
}
