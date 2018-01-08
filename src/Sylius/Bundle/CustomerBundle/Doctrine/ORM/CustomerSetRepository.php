<?php
declare(strict_types=1);

namespace Sylius\Bundle\CustomerBundle\Doctrine\ORM;

use Sylius\Component\Customer\Repository\CustomerSetRepositoryInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class CustomerSetRepository extends EntityRepository implements CustomerSetRepositoryInterface
{
}
