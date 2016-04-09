<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * UserSetting.
 *
 * @ORM\Table(name="user_settings")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserSettingRepository")
 */
class UserSetting
{
    const KEY_PRIMARY_MOBILE_NUMBER  = 'primary_mobile_number';
    const KEY_PRIMARY_EMAIL_ADDRESS  = 'primary_email_address';
    const KEY_TWO_FACTOR_AUTH_METHOD = 'two_factor_auth_method';
    const KEY_DISPLAY_NAME_OPTION    = 'display_name_option';

    const TWO_FACTOR_METHOD_SMS   = 'sms';
    const TWO_FACTOR_METHOD_EMAIL = 'email';
    const TWO_FACTOR_METHOD_NONE  = 'none';

    const DISPLAY_NAME_USERNAME                = 'username';
    const DISPLAY_NAME_FULL_NAME               = 'full_name';
    const DISPLAY_NAME_FIRST_NAME_LAST_INITIAL = 'first_name_last_initial';
    const DISPLAY_NAME_FIRST_INITIAL_LAST_NAME = 'first_initial_last_name';

    public static function getTwoFactorMethodOptions()
    {
        return [
            self::TWO_FACTOR_METHOD_SMS,
            self::TWO_FACTOR_METHOD_EMAIL,
            self::TWO_FACTOR_METHOD_NONE
        ];
    }

    public static function getDisplayNameOptions()
    {
        return [
            self::DISPLAY_NAME_USERNAME,
            self::DISPLAY_NAME_FULL_NAME,
            self::DISPLAY_NAME_FIRST_INITIAL_LAST_NAME,
            self::DISPLAY_NAME_FIRST_NAME_LAST_INITIAL
        ];
    }

    /**
     * @var int
     *
     * @ORM\Column(name="user_id", type="integer")
     * @ORM\Id
     */
    protected $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="key", type="string", length=255)
     * @ORM\Id
     */
    protected $key;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="text", nullable=true)
     */
    protected $value;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="userSettings")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return UserSetting
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
     * Set key
     *
     * @param string $key
     *
     * @return UserSetting
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Get key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return UserSetting
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return UserSetting
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
