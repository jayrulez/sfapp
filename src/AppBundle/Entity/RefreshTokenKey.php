<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="refresh_token_keys")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RefreshTokenKeyRepository")
 */
class RefreshTokenKey
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
	protected $id;

    /**
     * @ORM\Column(name="token_id", type="integer")
     */
	protected $tokenId;

    /**
     * @ORM\Column(type="string", length=255)
     */
	protected $keyName;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
	protected $keyValue;

    /**
     * @ORM\ManyToOne(targetEntity="RefreshToken", inversedBy="refreshTokenKeys", cascade={"persist"})
     * @ORM\JoinColumn(name="token_id", referencedColumnName="id")
     */
    protected $refreshToken;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set tokenId
     *
     * @param integer $tokenId
     *
     * @return RefreshTokenKey
     */
    public function setTokenId($tokenId)
    {
        $this->tokenId = $tokenId;

        return $this;
    }

    /**
     * Get tokenId
     *
     * @return integer
     */
    public function getTokenId()
    {
        return $this->tokenId;
    }

    /**
     * Set keyName
     *
     * @param string $keyName
     *
     * @return RefreshTokenKey
     */
    public function setKeyName($keyName)
    {
        $this->keyName = $keyName;

        return $this;
    }

    /**
     * Get keyName
     *
     * @return string
     */
    public function getKeyName()
    {
        return $this->keyName;
    }

    /**
     * Set keyValue
     *
     * @param string $keyValue
     *
     * @return RefreshTokenKey
     */
    public function setKeyValue($keyValue)
    {
        $this->keyValue = $keyValue;

        return $this;
    }

    /**
     * Get keyValue
     *
     * @return string
     */
    public function getKeyValue()
    {
        return $this->keyValue;
    }

    /**
     * Set refreshToken
     *
     * @param \AppBundle\Entity\RefreshToken $refreshToken
     *
     * @return RefreshTokenKey
     */
    public function setRefreshToken(\AppBundle\Entity\RefreshToken $refreshToken = null)
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }

    /**
     * Get refreshToken
     *
     * @return \AppBundle\Entity\RefreshToken
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }
}
