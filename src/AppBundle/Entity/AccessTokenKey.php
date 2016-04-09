<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="access_token_keys")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AccessTokenKeyRepository")
 */
class AccessTokenKey
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
     * @ORM\ManyToOne(targetEntity="AccessToken", inversedBy="accessTokenKeys", cascade={"persist"})
     * @ORM\JoinColumn(name="token_id", referencedColumnName="id")
     */
    protected $accessToken;

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
     * @return AccessTokenKey
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
     * @return AccessTokenKey
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
     * @return AccessTokenKey
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
     * Set accessToken
     *
     * @param \AppBundle\Entity\AccessToken $accessToken
     *
     * @return AccessTokenKey
     */
    public function setAccessToken(\AppBundle\Entity\AccessToken $accessToken = null)
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * Get accessToken
     *
     * @return \AppBundle\Entity\AccessToken
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }
}
