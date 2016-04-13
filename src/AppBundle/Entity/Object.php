<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Object.
 *
 * @ORM\Table(name="objects")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ObjectRepository")
 */
class Object
{
}
