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

use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Component\Customer\Model\CustomerSetInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Arvids Godjuks <arvids.godjuks@gmail.com>
 */
final class CustomerSetSetup implements CustomerSetSetupInterface
{
    /**
     * @var RepositoryInterface
     */
    private $customerSetRepository;

    /**
     * @var FactoryInterface
     */
    private $customerSetFactory;

    /**
     * @var ObjectManager
     */
    private $customerSetManager;

    /**
     * @param RepositoryInterface $customerSetRepository
     * @param FactoryInterface $customerSetFactory
     * @param ObjectManager $customerSetManager
     */
    public function __construct(
        RepositoryInterface $customerSetRepository,
        FactoryInterface $customerSetFactory,
        ObjectManager $customerSetManager
    ) {
        $this->customerSetRepository = $customerSetRepository;
        $this->customerSetFactory = $customerSetFactory;
        $this->customerSetManager = $customerSetManager;
    }

    /**
     * {@inheritdoc}
     */
    public function setup(InputInterface $input, OutputInterface $output): CustomerSetInterface
    {
        /** @var CustomerSetInterface $customerSet */
        $customerSet = $this->customerSetRepository->findOneBy([]);

        if (null === $customerSet) {
            $customerSet = $this->customerSetFactory->createNew();
            $customerSet->setCode('default');
            $customerSet->setName('Default');

            $this->customerSetManager->persist($customerSet);
        }

        $this->customerSetManager->flush();

        return $customerSet;
    }
}
