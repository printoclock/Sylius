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

namespace Sylius\Bundle\CoreBundle\Validator\Constraints;

use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class RegisteredUserValidator extends ConstraintValidator
{
    /**
     * @var RepositoryInterface
     */
    private $customerRepository;

    /**
     * @var ChannelInterface
     */
    private $channel;

    /**
     * @param RepositoryInterface $customerRepository
     */
    public function __construct(RepositoryInterface $customerRepository, ChannelContextInterface $channelContext)
    {
        $this->customerRepository = $customerRepository;
        $this->channel = $channelContext->getChannel();
    }

    /**
     * {@inheritdoc}
     */
    public function validate($customer, Constraint $constraint): void
    {
        $existingCustomer = $this->customerRepository->findOneBy([
            'email' => $customer->getEmail(),
            'customerSet' => $this->channel->getCustomerSet()
        ]);
        dump($existingCustomer);

        if (null !== $existingCustomer && null !== $existingCustomer->getUser()) {
            dump('add violation');
            $this->context->buildViolation($constraint->message)->atPath('email')->addViolation();
        }
        exit();
    }
}
