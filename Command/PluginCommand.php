<?php
/*
 * This file is part of the Hinagata
 *
 * Copyright(c) 2017 izayoi256 All Rights Reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\Hinagata\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PluginCommand extends \Knp\Command\Command
{
    protected function configure()
    {
        $this->setName('hinagata:command');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $app = $this->getSilexApplication();
    }
}