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

use AppBundle\Entity\Client;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;

class ClientFixture implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $request = Request::createFromGlobals();
        if (!$request->getUri()) {
            $request = Request::create('http://localhost:8000');
        }

        $model = new Client();
        $model->setClientId('client_id')
            ->setClientSecret('client_secret')
            ->setRedirectUri('http://localhost:8000');
        $manager->persist($model);

        $manager->flush();
    }
}
