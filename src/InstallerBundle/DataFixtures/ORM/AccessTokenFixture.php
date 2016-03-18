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

use AppBundle\Entity\AccessToken;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class AccessTokenFixture implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $model = new AccessToken();
        $model->setAccessToken('eeb5aa92bbb4b56373b9e0d00bc02d93')
            ->setTokenType('bearer')
            ->setClientId('client_id')
            ->setUsername('demo')
            ->setExpires(new \DateTime('+10 years'))
            ->setScope([
                'demoscope1',
                'demoscope2',
            ]);
        $manager->persist($model);

        $manager->flush();
    }
}
