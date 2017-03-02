<?php
/*
 * This file is part of the Hinagata
 *
 * Copyright(c) 2017 izayoi256 All Rights Reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\Hinagata\Form\Extension\Admin;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;

class ConfigTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $name = $builder->get('name');
        $options = $name->getOptions();
        $options['required'] = true;
        $type = $name->getType()->getName();
        $builder->add('name', $type, $options);
    }

    /**
     * @inheritdoc
     */
    public function getExtendedType()
    {
        return 'plugin_hinagata_admin_config';
    }
}
