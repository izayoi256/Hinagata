<?php
/*
 * This file is part of the Hinagata
 *
 * Copyright(c) 2017 izayoi256 All Rights Reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\Hinagata\Service;

use Eccube\Application;

class PluginService
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }
}