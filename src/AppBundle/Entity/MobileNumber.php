<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * MobileNumber.
 *
 * @ORM\Table(name="mobile_number")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\MobileNumberRepository")
 *
 * @UniqueEntity(fields={"countryCode", "number"}, errorPath="number", message="This mobile number is already in use.")
 */
class MobileNumber
{
    /**
     * @var string
     *
     * @ORM\Column(name="country_code", type="string", length=8)
     * @ORM\Id
     */
    protected $countryCode;

    /**
     * @var string
     *
     * @ORM\Column(name="number", type="string", length=16)
     * @ORM\Id
     */
    protected $number;

    /**
     * @var int
     *
     * @ORM\Column(name="user_id", type="integer")
     */
    protected $userId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="verified", type="boolean", options={ "default": false })
     */
    protected $verified;

    /**
     * @var string
     *
     * @ORM\Column(name="verified_at", type="datetime", nullable=true)
     */
    protected $verifiedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    protected $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="mobileNumbers", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var string
     *
     */
    public function getFullMobileNumber()
    {
    	$fullNumber = '';

    	if(!empty($this->countryCode))
    	{
    		$fullNumber = $this->countryCode;
    	}

    	$fullNumber .= $this->number;

    	return $fullNumber;
    }

    /**
     * Set countryCode
     *
     * @param string $countryCode
     *
     * @return MobileNumber
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * Get countryCode
     *
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * Set number
     *
     * @param string $number
     *
     * @return MobileNumber
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return MobileNumber
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set verified
     *
     * @param boolean $verified
     *
     * @return MobileNumber
     */
    public function setVerified($verified)
    {
        $this->verified = $verified;

        return $this;
    }

    /**
     * Get verified
     *
     * @return boolean
     */
    public function getVerified()
    {
        return $this->verified;
    }

    /**
     * Set verifiedAt
     *
     * @param \DateTime $verifiedAt
     *
     * @return MobileNumber
     */
    public function setVerifiedAt($verifiedAt)
    {
        $this->verifiedAt = $verifiedAt;

        return $this;
    }

    /**
     * Get verifiedAt
     *
     * @return \DateTime
     */
    public function getVerifiedAt()
    {
        return $this->verifiedAt;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return MobileNumber
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return MobileNumber
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return MobileNumber
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
