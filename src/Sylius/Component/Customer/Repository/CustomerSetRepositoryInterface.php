<?php
declare(strict_types=1);

namespace Sylius\Component\Customer\Repository;

use Sylius\Component\Channel\Model\ChannelInterface;

interface CustomerSetRepositoryInterface
{

    public function findOneByEmailAndChannel(string $email, ChannelInterface $channel): ?CustomerSetRepositoryInterface;
}
