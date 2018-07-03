<?php

/*
 * This file is part of the tuowt/Zhifu99\.
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Zhifu99\MiniProgram;

use Zhifu99\BasicService;
use Zhifu99\Kernel\ServiceContainer;

/**
 * Class Application.
 *
 * @property \Zhifu99\MiniProgram\Auth\AccessToken            $access_token
 * @property \Zhifu99\MiniProgram\DataCube\Client             $data_cube
 * @property \Zhifu99\MiniProgram\AppCode\Client              $app_code
 * @property \Zhifu99\MiniProgram\Auth\Client                 $auth
 * @property \Zhifu99\MiniProgram\Encryptor                   $encryptor
 * @property \Zhifu99\MiniProgram\TemplateMessage\Client      $template_message
 * @property \Zhifu99\BasicService\Media\Client               $media
 * @property \Zhifu99\BasicService\ContentSecurity\Client     $content_security
 */
class Application extends ServiceContainer
{
    /**
     * @var array
     */
    protected $providers = [
        Auth\ServiceProvider::class,
        DataCube\ServiceProvider::class,
        AppCode\ServiceProvider::class,
        Server\ServiceProvider::class,
        TemplateMessage\ServiceProvider::class,
        CustomerService\ServiceProvider::class,
        Store\ServiceProvider::class,
        // Base services
        BasicService\Media\ServiceProvider::class,
        BasicService\ContentSecurity\ServiceProvider::class,
    ];
}
