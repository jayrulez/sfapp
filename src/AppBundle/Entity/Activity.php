<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Activity.
 *
 * @ORM\Table(name="activities")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ActivityRepository")
 */
class Activity
{
}
