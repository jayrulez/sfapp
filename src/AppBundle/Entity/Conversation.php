<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Conversation.
 *
 * @ORM\Table(name="conversations")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ConversationRepository")
 */
class Conversation
{
}
