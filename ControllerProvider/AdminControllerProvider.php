<?php
/*
 * This file is part of the Hinagata
 *
 * Copyright(c) 2017 izayoi256 All Rights Reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\Hinagata\ControllerProvider;

use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;

class AdminControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        /** @var ControllerCollection $c */
        $c = $app['controllers_factory'];

        // 強制SSL
        if ($app['config']['force_ssl'] == \Eccube\Common\Constant::ENABLED) {
            $c->requireHttps();
        }

        $c->match('/plugin/Hinagata/config', 'Plugin\Hinagata\Controller\Admin\ConfigController::index')->bind('plugin_Hinagata_config');

        return $c;
    }
}
