<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Reaction.
 *
 * @ORM\Table(name="reactions")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ReactionRepository")
 */
class Reaction
{
}
