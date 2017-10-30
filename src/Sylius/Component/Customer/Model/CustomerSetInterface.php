<?php

declare(strict_types=1);

namespace Sylius\Component\Customer\Model;

use Sylius\Component\Resource\Model\CodeAwareInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

interface CustomerSetInterface extends ResourceInterface, CodeAwareInterface
{

    public function getName(): ?string;

    public function setName(string $name): void;
}
