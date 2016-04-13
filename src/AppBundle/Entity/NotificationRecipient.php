<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * NotificationRecipient.
 *
 * @ORM\Table(name="notification_recipients")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NotificationRecipientRepository")
 */
class NotificationRecipient
{
}
