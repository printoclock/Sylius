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

namespace Sylius\Bundle\UserBundle\Provider;

use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\User\Canonicalizer\CanonicalizerInterface;
use Sylius\Component\User\Repository\UserRepositoryInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author Arvids Godjuks <arvids.gosjuks@gmail.com>
 */
class UsernameOrEmailAndCustomerSetProvider extends AbstractUserProvider
{
    /**
     * @var ChannelInterface
     */
    protected $channel;

    /**
     * @param string $supportedUserClass FQCN
     * @param UserRepositoryInterface $userRepository
     * @param CanonicalizerInterface $canonicalizer
     */
    public function __construct(
        string $supportedUserClass,
        UserRepositoryInterface $userRepository,
        CanonicalizerInterface $canonicalizer,
        ChannelContextInterface $channelContext
    ) {
        parent::__construct($supportedUserClass, $userRepository, $canonicalizer);
        $this->channel = $channelContext->getChannel();
    }

    /**
     * {@inheritdoc}
     */
    protected function findUser(string $usernameOrEmail): ?UserInterface
    {
        if (filter_var($usernameOrEmail, FILTER_VALIDATE_EMAIL)) {
            return $this->userRepository->findOneByEmailAndCustomerSet($usernameOrEmail, $this->channel->getCustomerSet());
        }

        return $this->userRepository->findOneBy(['usernameCanonical' => $usernameOrEmail]);
    }
}
