<?php
declare(strict_types=1);

namespace Sylius\Bundle\CustomerBundle\Doctrine\ORM;

use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Customer\Repository\CustomerSetRepositoryInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class CustomerSetRepository extends EntityRepository implements CustomerSetRepositoryInterface
{

    /**
     * @param string $email
     * @param ChannelInterface $channel
     *
     * @return null|CustomerSetRepositoryInterface
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByEmailAndChannel(string $email, ChannelInterface $channel): ?CustomerSetRepositoryInterface
    {
        $qb = $this->createQueryBuilder('c');

        $qb
            ->innerJoin('c.customerSet', 'cs')
            ->innerJoin('cs.channel', 'ch')
            ->where('c.email = :email')
            ->andWhere('ch.code = :channel')
            ->setParameter('email', $email)
            ->setParameter('channel', $channel)
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }

}
