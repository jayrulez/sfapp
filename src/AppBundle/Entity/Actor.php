<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Actor.
 *
 * @ORM\Table(name="actors")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ActorRepository")
 */
class Actor
{
}