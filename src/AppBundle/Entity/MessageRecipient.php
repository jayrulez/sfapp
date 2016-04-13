<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * MessageRecipient.
 *
 * @ORM\Table(name="message_recipients")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MessageRecipientRepository")
 */
class MessageRecipient
{
}
