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

namespace Sylius\Bundle\CoreBundle\Doctrine\ORM;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Repository\CustomerRepositoryInterface;

class CustomerRepository extends EntityRepository implements CustomerRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function countCustomers(): int
    {
        return (int) $this->createQueryBuilder('o')
            ->select('COUNT(o.id)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function findLatest(int $count): array
    {
        return $this->createQueryBuilder('o')
            ->addOrderBy('o.createdAt', 'DESC')
            ->setMaxResults($count)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @inheritdoc
     */
    public function findOneByEmailAndChannel(string $email, ChannelInterface $channel): ?CustomerInterface
    {
        $qb = $this->createQueryBuilder('c');

        $qb
            ->innerJoin('c.customerSet', 'cs')
            ->innerJoin('cs.channels', 'ch')
            ->where('c.email = :email')
            ->andWhere('ch.code = :channel')
            ->setParameter('email', $email)
            ->setParameter('channel', $channel->getCode())
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }
}
