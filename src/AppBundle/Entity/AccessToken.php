<?php

namespace AppBundle\Entity;

use FOS\OAuthServerBundle\Entity\AccessToken as BaseAccessToken;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="access_tokens")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AccessTokenRepository")
 */
class AccessToken extends BaseAccessToken
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Client")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $client;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     */
    protected $user;

    /**
     * @ORM\OneToMany(targetEntity="AccessTokenKey", mappedBy="accessToken")
     */
    private $accessTokenKeys;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->accessTokenKeys = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add accessTokenKey
     *
     * @param \AppBundle\Entity\AccessTokenKey $accessTokenKey
     *
     * @return AccessToken
     */
    public function addAccessTokenKey(\AppBundle\Entity\AccessTokenKey $accessTokenKey)
    {
        $this->accessTokenKeys[] = $accessTokenKey;

        return $this;
    }

    /**
     * Remove accessTokenKey
     *
     * @param \AppBundle\Entity\AccessTokenKey $accessTokenKey
     */
    public function removeAccessTokenKey(\AppBundle\Entity\AccessTokenKey $accessTokenKey)
    {
        $this->accessTokenKeys->removeElement($accessTokenKey);
    }

    /**
     * Get accessTokenKeys
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAccessTokenKeys()
    {
        return $this->accessTokenKeys;
    }
}
