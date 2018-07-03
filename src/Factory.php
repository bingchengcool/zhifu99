<?php

/*
 * This file is part of the tuowt/Zhifu99.
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Zhifu99;

/**
 * Class Factory.
 *
 * @method static \Zhifu99\Payment\Application            payment(array $config)
 * @method static \Zhifu99\MiniProgram\Application        miniProgram(array $config)
 * @method static \Zhifu99\BasicService\Application       basicService(array $config)
 */
class Factory
{
    /**
     * @param string $name
     * @param array  $config
     *
     * @return \Zhifu99\\Kernel\ServiceContainer
     */
    public static function make($name, $config)
    {
        $namespace = Kernel\Support\Str::studly($name);
        $application = "\\Zhifu99\\{$namespace}\\Application";

        return new $application($config);
    }

    /**
     * Dynamically pass methods to the application.
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        return self::make($name, ...$arguments);
    }
}
