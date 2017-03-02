<?php
/*
 * This file is part of the Hinagata
 *
 * Copyright(c) 2017 izayoi256 All Rights Reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\Hinagata;

use Eccube\Application;
use Eccube\Event\EventArgs;
use Plugin\Hinagata\Util\Version;

class PluginEvent
{
    /** @var \Eccube\Application $app */
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param EventArgs $event
     */
    public function onEvent($event)
    {
        $this->app['eccube.plugin.hinagata.event.event']->onEvent($event);
    }

    /**
     * @param EventArgs $event
     */
    public function onEventLegacy($event)
    {
        if (Version::isSupportNewHookPoint()) {
            $this->app['eccube.plugin.hinagata.event.legacy_event']->onEvent($event);
        }
    }
}
