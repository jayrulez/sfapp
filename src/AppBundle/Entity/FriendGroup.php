<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * FriendGroup.
 *
 * @ORM\Table(name="friend_groups")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FriendGroupRepository")
 */
class FriendGroup
{
}
