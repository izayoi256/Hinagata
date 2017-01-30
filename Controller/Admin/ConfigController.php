<?php
/*
 * This file is part of the Hinagata
 *
 * Copyright(c) 2017 izayoi256 All Rights Reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\Hinagata\Controller\Admin;

use Eccube\Application;
use Eccube\Controller\AbstractController;
use Eccube\Event\EventArgs;
use Plugin\Hinagata\Event\PluginEvents;
use Symfony\Component\HttpFoundation\Request;

class ConfigController extends AbstractController
{
    /**
     * @param Application $app
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Application $app, Request $request)
    {
        $Info = $app['eccube.plugin.hinagata.repository.info']->get();
        $builder = $app['form.factory']->createBuilder('plugin_hinagata_admin_config', $Info);

        $event = new EventArgs(compact('builder', 'Info'), $request);
        $app['eccube.event.dispatcher']->dispatch(PluginEvents::PLUGIN_HINAGATA_ADMIN_CONFIG_INITIALIZE, $event);

        $form = $builder->getForm();

        if ('POST' === $request->getMethod()) {

            $form->handleRequest($request);

            if ($form->isValid()) {

                $Info = $form->getData();
                $app['orm.em']->persist($Info);
                $app['orm.em']->flush();

                $event = new EventArgs(compact('form', 'Info'), $request);
                $app['eccube.event.dispatcher']->dispatch(PluginEvents::PLUGIN_HINAGATA_ADMIN_CONFIG_COMPLETE, $event);

                if ($event->hasResponse()) {
                    return $event->getResponse();
                }

                $app->addSuccess('plugin.hinagata.admin.setting.complete', 'admin');
                return $app->redirect($app->url('plugin_Hinagata_config'));
            }
            $app->addError('plugin.hinagata.admin.setting.failed', 'admin');
        }

        return $app->render('Hinagata/Resource/template/admin/config.twig', array(
            'form' => $form->createView(),
        ));
    }
}
