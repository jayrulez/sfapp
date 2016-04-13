<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * ConversationParticipant.
 *
 * @ORM\Table(name="conversation_participants")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ConversationParticipantRepository")
 */
class ConversationParticipant
{
}
