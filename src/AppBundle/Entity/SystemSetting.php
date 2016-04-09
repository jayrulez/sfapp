<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * SystemSetting.
 *
 * @ORM\Table(name="system_settings")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SystemSettingRepository")
 */
class SystemSetting
{
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
}
