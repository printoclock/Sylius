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

namespace Sylius\Bundle\CoreBundle\Form\Type\Customer;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

final class CustomerGuestType extends AbstractResourceType
{
    /**
     * @var RepositoryInterface
     */
    private $customerRepository;

    /**
     * @var FactoryInterface
     */
    private $customerFactory;

    /**
     * @var ChannelInterface
     */
    private $channel;

    /**
     * @param string $dataClass
     * @param array $validationGroups
     * @param RepositoryInterface $customerRepository
     * @param FactoryInterface $customerFactory
     * @param ChannelContextInterface $channelContext
     */
    public function __construct(
        string $dataClass,
        array $validationGroups,
        RepositoryInterface $customerRepository,
        FactoryInterface $customerFactory,
        ChannelContextInterface $channelContext
    ) {
        parent::__construct($dataClass, $validationGroups);

        $this->customerRepository = $customerRepository;
        $this->customerFactory = $customerFactory;
        $this->channel = $channelContext->getChannel();
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options = []): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'sylius.form.customer.email',
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event): void {
                $data = $event->getData();

                if (!isset($data['email'])) {
                    return;
                }

                /** @var CustomerInterface $customer */
                $customer = $this->customerRepository->findOneBy([
                    'email' => $data['email'],
                    'customerSet' => $this->channel->getCustomerSet(),
                ]);

                // assign existing customer or create a new one
                $form = $event->getForm();
                if (null !== $customer && null === $customer->getUser()) {
                    $form->setData($customer);

                    return;
                }

                /** @var CustomerInterface $customer */
                $customer = $this->customerFactory->createNew();
                $customer->setEmail($data['email']);
                $customer->setCustomerSet($this->channel->getCustomerSet());

                $form->setData($customer);
            })
            ->setDataLocked(false)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'sylius_customer_guest';
    }
}
