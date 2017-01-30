<?php
/*
 * This file is part of the Hinagata
 *
 * Copyright(c) 2017 izayoi256 All Rights Reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\Hinagata\ServiceProvider;

use Monolog\Handler\FingersCrossed\ErrorLevelActivationStrategy;
use Monolog\Handler\FingersCrossedHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Plugin\Hinagata\Event\Event;
use Plugin\Hinagata\Event\LegacyEvent;
use Silex\Application as BaseApplication;
use Silex\ServiceProviderInterface;

require_once(__DIR__ . '/../log.php');

class PluginServiceProvider implements ServiceProviderInterface
{
    public function register(BaseApplication $app)
    {
        $app->mount('', new \Plugin\Hinagata\ControllerProvider\FrontControllerProvider());
        $app->mount(sprintf('/%s/', trim($app['config']['admin_route'])) , new \Plugin\Hinagata\ControllerProvider\AdminControllerProvider());

        $app['eccube.plugin.hinagata.repository.info'] = $app->share(function () use ($app) {
            return $app['orm.em']->getRepository('Plugin\Hinagata\Entity\Info');
        });

        $app['form.types'] = $app->share($app->extend('form.types', function ($types) use ($app) {
            $types[] = new \Plugin\Hinagata\Form\Type\Admin\ConfigType($app);
            return $types;
        }));

        $app['form.type.extensions'] = $app->share($app->extend('form.type.extensions', function ($extensions) {
            $extensions[] = new \Plugin\Hinagata\Form\Extension\Admin\ConfigTypeExtension();
            return $extensions;
        }));

        $app['eccube.plugin.hinagata.service.hinagata'] = $app->share(function () use ($app) {
            return new \Plugin\Hinagata\Service\PluginService($app);
        });

        $app['eccube.plugin.hinagata.event.event'] = $app->share(function () use ($app) {
            return new Event($app);
        });

        $app['eccube.plugin.hinagata.event.legacy_event'] = $app->share(function () use ($app) {
            return new LegacyEvent($app);
        });

        $file = sprintf('%s/../Resource/locale/message.%s.yml', __DIR__, $app['locale']);
        if (file_exists($file)) {
            $app['translator']->addResource('yaml', $file, $app['locale']);
        }

        $app['config'] = $app->share($app->extend('config', function ($configAll) {
            $configAll['nav'] = array_map(
                function ($nav) {
                    if ($nav['id'] == 'product') {
//                        $nav['child'][] = array(
//                            'id' => '',
//                            'name' => '',
//                            'url' => '',
//                        );
                    }
                    return $nav;
                },
                $configAll['nav']
            );
            return $configAll;
        }));

        $app['monolog.logger.hinagata'] = $app->share(function ($app) {

            /** @var \Symfony\Bridge\Monolog\Logger $logger */
            $logger = new $app['monolog.logger.class']('hinagata');

            $filename = sprintf('%s/app/log/hinagata.log', $app['config']['root_dir']);
            $RotateHandler = new RotatingFileHandler($filename, $app['config']['log']['max_files'], Logger::INFO);
            $RotateHandler->setFilenameFormat('hinagata_{date}', 'Y-m-d');

            $logger->pushHandler(
                new FingersCrossedHandler(
                    $RotateHandler,
                    new ErrorLevelActivationStrategy(Logger::ERROR),
                    0,
                    true,
                    true,
                    Logger::INFO
                )
            );

            return $logger;
        });

        if (isset($app['console'])) {
            $app['console']->add(new \Plugin\Hinagata\Command\PluginCommand());
        }
    }

    public function boot(BaseApplication $app)
    {
    }
}
