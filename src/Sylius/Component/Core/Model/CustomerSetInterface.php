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

namespace Sylius\Component\Core\Model;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Customer\Model\CustomerSetInterface as BaseCustomerSetInterface;

interface CustomerSetInterface extends BaseCustomerSetInterface
{

    /**
     * @param ChannelInterface $channel
     */
    public function addChannel(ChannelInterface $channel): void;

    /**
     * @param ChannelInterface $channel
     */
    public function removeChannel(ChannelInterface $channel): void;

    /**
     * @param ChannelInterface $channel
     *
     * @return bool
     */
    public function hasChannel(ChannelInterface $channel): bool;

    /**
     * @return Collection
     */
    public function getChannels(): Collection;
}
