<?php

/**
 * This file is part of the authbucket/oauth2-symfony-bundle package.
 *
 * (c) Wong Hoi Sing Edison <hswong3i@pantarei-design.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Entity;

use AuthBucket\Bundle\OAuth2Bundle\Entity\Client as AbstractClient;
use Doctrine\ORM\Mapping as ORM;

/**
 * Client.
 *
 * @ORM\Table(name="authbucket_oauth2_client")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ClientRepository")
 */
class Client extends AbstractClient
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
