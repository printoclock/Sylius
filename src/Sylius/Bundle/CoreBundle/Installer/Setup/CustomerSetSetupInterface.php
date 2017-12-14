<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Bundle\CoreBundle\Installer\Setup;

use Sylius\Component\Customer\Model\CustomerSetInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Arvids Godjuks <arvids.godjuks@gmail.com>
 */
interface CustomerSetSetupInterface
{
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return CustomerSetInterface
     */
    public function setup(InputInterface $input, OutputInterface $output): CustomerSetInterface;
}
