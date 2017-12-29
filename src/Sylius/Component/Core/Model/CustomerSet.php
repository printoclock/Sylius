<?php
declare(strict_types=1);

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Component\Core\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Customer\Model\CustomerSet as BaseCustomerSet;

class CustomerSet extends BaseCustomerSet implements CustomerSetInterface
{
    /**
     * @var Collection|Channel[]
     */
    protected $channels;

    public function __construct()
    {
        $this->channels = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function addChannel(ChannelInterface $channel): void
    {
        if (!$this->hasChannel($channel)) {
            $this->channels[] = $channel;
            $channel->setCustomerSet($this);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeChannel(ChannelInterface $channel): void
    {
        $this->channels->removeElement($channel);
        $channel->setCustomerSet(null);
    }

    /**
     * {@inheritdoc}
     */
    public function hasChannel(ChannelInterface $channel): bool
    {
        return $this->channels->contains($channel);
    }

    /**
     * {@inheritdoc}
     */
    public function getChannels(): Collection
    {
        return $this->channels;
    }

}
