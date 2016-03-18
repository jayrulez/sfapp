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

use AppBundle\Entity\Code;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class CodeFixture implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $model = new Code();
        $model->setCode('f0c68d250bcc729eb780a235371a9a55')
            ->setClientId('client_id')
            ->setUsername('demo')
            ->setRedirectUri('http://localhost:8000/')
            ->setExpires(new \DateTime('+10 minutes'))
            ->setScope([
                'demoscope1',
                'demoscope2',
            ]);
        $manager->persist($model);

        $manager->flush();
    }
}
