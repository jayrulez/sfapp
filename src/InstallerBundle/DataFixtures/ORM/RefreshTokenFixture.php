<?php

/**
 * This file is part of the authbucket/oauth2-symfony-bundle package.
 *
 * (c) Wong Hoi Sing Edison <hswong3i@pantarei-design.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace InstallerBundle\DataFixtures\ORM;

use AppBundle\Entity\RefreshToken;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class RefreshTokenFixture implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $model = new RefreshToken();
        $model->setRefreshToken('5ff43cbc27b54202c6fd8bb9c2a308ce')
            ->setClientId('client_id')
            ->setUsername('demo')
            ->setExpires(new \DateTime('-1 days'))
            ->setScope([
                'demoscope1',
            ]);
        $manager->persist($model);

        $manager->flush();
    }
}
