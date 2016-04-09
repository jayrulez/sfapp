<?php

namespace AppBundle\Entity;

use FOS\OAuthServerBundle\Entity\RefreshToken as BaseRefreshToken;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="refresh_tokens")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RefreshTokenRepository")
 */
class RefreshToken extends BaseRefreshToken
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
     * @ORM\OneToMany(targetEntity="RefreshTokenKey", mappedBy="refreshToken")
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
     * @param \AppBundle\Entity\RefreshTokenKey $accessTokenKey
     *
     * @return RefreshToken
     */
    public function addAccessTokenKey(\AppBundle\Entity\RefreshTokenKey $accessTokenKey)
    {
        $this->accessTokenKeys[] = $accessTokenKey;

        return $this;
    }

    /**
     * Remove accessTokenKey
     *
     * @param \AppBundle\Entity\RefreshTokenKey $accessTokenKey
     */
    public function removeAccessTokenKey(\AppBundle\Entity\RefreshTokenKey $accessTokenKey)
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
