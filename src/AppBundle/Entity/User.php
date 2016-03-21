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

use AuthBucket\OAuth2\Model\ModelInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User.
 *
 * @ORM\Table(name="authbucket_oauth2_user")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\UserRepository")
 *
 * @UniqueEntity(fields={"username"}, errorPath="username", message="This username is already in use.")
 */
class User implements ModelInterface, UserInterface
{
    const TWO_FACTOR_METHOD_SMS = 'sms';
    const TWO_FACTOR_METHOD_EMAIL = 'email';

    const DISPLAY_NAME_USERNAME = 'username';
    const DISPLAY_NAME_FIRST_NAME_LAST_INITIAL = 'first_name_last_initial';
    const DISPLAY_NAME_FIRST_INITIAL_LAST_NAME = 'first_initial_last_name';

    const GENDER_MALE = 'male';
    const GENDER_FEMALE = 'female';
    const GENDER_OTHER = 'other';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=32, unique=true)
     *
     * @Assert\NotBlank(message="Username is required.")
     * @Assert\Length(
     *      min=3, 
     *      max=32, 
     *      minMessage="Username must be at least {{ limit }} characters long.",
     *      maxMessage="Username can be at most {{ limit }} characters long."
     * )
     */
    protected $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     *
     * @Assert\NotBlank(message="Password is required.")
     * @Assert\Length(
     *      min=6,
     *      minMessage="Password must be at least {{ limit }} characters long."
     * )
     */
    protected $password;

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="array")
     */
    protected $roles;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=40)
     *
     * @Assert\NotBlank(message="First name is required.")
     * @Assert\Length(
     *      max=40, 
     *      maxMessage="First name can be at most {{ limit }} characters long."
     * )
     */
    protected $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=40)
     *
     * @Assert\NotBlank(message="Last name is required.")
     * @Assert\Length(
     *      max=40, 
     *      maxMessage="Last name can be at most {{ limit }} characters long."
     * )
     */
    protected $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=32, nullable=true)
     */
    protected $gender;

    /**
     * @var string
     *
     * @ORM\Column(name="date_of_birth", type="date", nullable=true)
     */
    protected $dateOfBirth;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar", type="text", nullable=true)
     */
    protected $avatar;

    /**
     * @var string
     *
     * @ORM\Column(name="primary_mobile_number", type="string", length=24, nullable=true)
     */
    protected $primaryMobileNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="primary_email_address", type="string", length=50, nullable=true)
     */
    protected $primaryEmailAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="two_factor_method", type="string", length=32, nullable=true)
     */
    protected $twoFactorMethod;

    /**
     * @var string
     *
     * @ORM\Column(name="display_name_option", type="string", length=32, nullable=true)
     */
    protected $displayNameOption;

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
     * @ORM\OneToMany(targetEntity="MobileNumber", mappedBy="user", cascade={"persist"})
     *
     * @Assert\Valid
     */
    protected $mobileNumbers;

    /**
     * @ORM\OneToMany(targetEntity="EmailAddress", mappedBy="user", cascade={"persist"})
     *
     * @Assert\Valid
     */
    protected $emailAddresses;

    /**
     * @ORM\OneToMany(targetEntity="Device", mappedBy="user", cascade={"persist"})
     */
    protected $devices;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username.
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password.
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set roles.
     *
     * @param array $roles
     *
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get roles.
     *
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    public function getSalt()
    {
    }

    public function eraseCredentials()
    {
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->mobileNumbers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->emailAddresses = new \Doctrine\Common\Collections\ArrayCollection();
        $this->devices = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set gender
     *
     * @param string $gender
     *
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set dateOfBirth
     *
     * @param \DateTime $dateOfBirth
     *
     * @return User
     */
    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    /**
     * Get dateOfBirth
     *
     * @return \DateTime
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     *
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set primaryMobileNumber
     *
     * @param string $primaryMobileNumber
     *
     * @return User
     */
    public function setPrimaryMobileNumber($primaryMobileNumber)
    {
        $this->primaryMobileNumber = $primaryMobileNumber;

        return $this;
    }

    /**
     * Get primaryMobileNumber
     *
     * @return string
     */
    public function getPrimaryMobileNumber()
    {
        return $this->primaryMobileNumber;
    }

    /**
     * Set primaryEmailAddress
     *
     * @param string $primaryEmailAddress
     *
     * @return User
     */
    public function setPrimaryEmailAddress($primaryEmailAddress)
    {
        $this->primaryEmailAddress = $primaryEmailAddress;

        return $this;
    }

    /**
     * Get primaryEmailAddress
     *
     * @return string
     */
    public function getPrimaryEmailAddress()
    {
        return $this->primaryEmailAddress;
    }

    /**
     * Set twoFactorMethod
     *
     * @param string $twoFactorMethod
     *
     * @return User
     */
    public function setTwoFactorMethod($twoFactorMethod)
    {
        $this->twoFactorMethod = $twoFactorMethod;

        return $this;
    }

    /**
     * Get twoFactorMethod
     *
     * @return string
     */
    public function getTwoFactorMethod()
    {
        return $this->twoFactorMethod;
    }

    /**
     * Set displayNameOption
     *
     * @param string $displayNameOption
     *
     * @return User
     */
    public function setDisplayNameOption($displayNameOption)
    {
        $this->displayNameOption = $displayNameOption;

        return $this;
    }

    /**
     * Get displayNameOption
     *
     * @return string
     */
    public function getDisplayNameOption()
    {
        return $this->displayNameOption;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return User
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
     * @return User
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
     * Add mobileNumber
     *
     * @param \AppBundle\Entity\MobileNumber $mobileNumber
     *
     * @return User
     */
    public function addMobileNumber(\AppBundle\Entity\MobileNumber $mobileNumber)
    {
        $this->mobileNumbers[] = $mobileNumber;

        return $this;
    }

    /**
     * Remove mobileNumber
     *
     * @param \AppBundle\Entity\MobileNumber $mobileNumber
     */
    public function removeMobileNumber(\AppBundle\Entity\MobileNumber $mobileNumber)
    {
        $this->mobileNumbers->removeElement($mobileNumber);
    }

    /**
     * Get mobileNumbers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMobileNumbers()
    {
        return $this->mobileNumbers;
    }

    /**
     * Add emailAddress
     *
     * @param \AppBundle\Entity\EmailAddress $emailAddress
     *
     * @return User
     */
    public function addEmailAddress(\AppBundle\Entity\EmailAddress $emailAddress)
    {
        $this->emailAddresses[] = $emailAddress;

        return $this;
    }

    /**
     * Remove emailAddress
     *
     * @param \AppBundle\Entity\EmailAddress $emailAddress
     */
    public function removeEmailAddress(\AppBundle\Entity\EmailAddress $emailAddress)
    {
        $this->emailAddresses->removeElement($emailAddress);
    }

    /**
     * Get emailAddresses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmailAddresses()
    {
        return $this->emailAddresses;
    }

    /**
     * Add device
     *
     * @param \AppBundle\Entity\Device $device
     *
     * @return User
     */
    public function addDevice(\AppBundle\Entity\Device $device)
    {
        $this->devices[] = $device;

        return $this;
    }

    /**
     * Remove device
     *
     * @param \AppBundle\Entity\Device $device
     */
    public function removeDevice(\AppBundle\Entity\Device $device)
    {
        $this->devices->removeElement($device);
    }

    /**
     * Get devices
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDevices()
    {
        return $this->devices;
    }
}
