<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CommentObject.
 *
 * @ORM\Table(name="comment_objects")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommentObjectRepository")
 */
class CommentObject
{
}
CommentObject