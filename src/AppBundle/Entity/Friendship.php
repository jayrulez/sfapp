<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Friendship.
 *
 * @ORM\Table(name="friendships")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FriendshipRepository")
 */
class Friendship
{
}
