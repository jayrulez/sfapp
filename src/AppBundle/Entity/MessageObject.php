<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * MessageObject.
 *
 * @ORM\Table(name="message_objects")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MessageObjectRepository")
 */
class MessageObject
{
}
