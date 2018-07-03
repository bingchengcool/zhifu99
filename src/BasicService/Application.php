<?php

/*
 * This file is part of the tuowt/Zhifu99\.
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Zhifu99\BasicService;

use Zhifu99\Kernel\ServiceContainer;

/**
 * Class Application.
 *
 * @property \Zhifu99\BasicService\Jssdk\Client            $jssdk
 * @property \Zhifu99\BasicService\Media\Client            $media
 * @property \Zhifu99\BasicService\QrCode\Client           $qrcode
 * @property \Zhifu99\BasicService\Url\Client              $url
 * @property \Zhifu99\BasicService\ContentSecurity\Client  $content_security
 */
class Application extends ServiceContainer
{
    /**
     * @var array
     */
    protected $providers = [
        Jssdk\ServiceProvider::class,
        QrCode\ServiceProvider::class,
        Media\ServiceProvider::class,
        Url\ServiceProvider::class,
        ContentSecurity\ServiceProvider::class,
    ];
}
