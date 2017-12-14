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

namespace Sylius\Bundle\CoreBundle\Form\EventSubscriber;

use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Customer\Model\CustomerSet;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Webmozart\Assert\Assert;

final class CustomerRegistrationFormSubscriber implements EventSubscriberInterface
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
     * @param ChannelInterface $channel
     */
    public function __construct(RepositoryInterface $customerRepository, ChannelInterface $channel)
    {
        $this->customerRepository = $customerRepository;
        $this->channel = $channel;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SUBMIT => 'preSubmit',
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException
     */
    public function preSubmit(FormEvent $event): void
    {
        $rawData = $event->getData();
        $form = $event->getForm();
        $data = $form->getData();

        $form->add('customerSet', EntityType::class, ['class' => CustomerSet::class]);
        $rawData['customerSet'] = $this->channel->getCustomerSet()->getId();
        $event->setData($rawData);

        Assert::isInstanceOf($data, CustomerInterface::class);

        // if email is not filled in, go on
        if (!isset($rawData['email']) || empty($rawData['email'])) {
            return;
        }
        $existingCustomer = $this->customerRepository->findOneBy([
            'email' => $rawData['email'],
            'customerSet' => $this->channel->getCustomerSet(),
        ]);
        if (null === $existingCustomer || null !== $existingCustomer->getUser()) {
            return;
        }

        $existingCustomer->setUser($data->getUser());
        $form->setData($existingCustomer);
    }
}
