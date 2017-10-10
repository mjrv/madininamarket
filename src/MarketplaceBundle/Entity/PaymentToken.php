<?php

namespace MarketplaceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Payum\Core\Model\Token;

/**
 * PaymentToken
 *
 * @ORM\Table(name="payment_token")
 * @ORM\Entity(repositoryClass="MarketplaceBundle\Repository\PaymentTokenRepository")
 */
class PaymentToken extends Token
{
    
}

