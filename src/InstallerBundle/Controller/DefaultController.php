<?php

namespace InstallerBundle\Controller;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\Persistence\PersistentObject;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/install")
     */
    public function indexAction()
    {
        $conn = $this->get('database_connection');
        $em = $this->get('doctrine')->getManager();

        $params = $conn->getParams();
        $name = isset($params['path']) ? $params['path'] : (isset($params['dbname']) ? $params['dbname'] : false);

        try {
            //$conn->getSchemaManager()->dropDatabase($name);
            $conn->getSchemaManager()->createDatabase($name);
            //$conn->getSchemaManager()->dropAndCreateDatabase($name);
            $conn->close();
        } catch (\Exception $e) {
            throw $e;
        }

        $classes = [];
        foreach ($this->container->getParameter('authbucket_oauth2.model') as $class) {
            $classes[] = $em->getClassMetadata($class);
        }

        PersistentObject::setObjectManager($em);
        $tool = new SchemaTool($em);
        $tool->dropSchema($classes);
        $tool->createSchema($classes);

        $purger = new ORMPurger();
        $executor = new ORMExecutor($em, $purger);

        $loader = new Loader();
        $loader->loadFromDirectory(__DIR__.'/../DataFixtures/ORM');
        $executor->execute($loader->getFixtures());

        return $this->redirect($this->get('router')->generate('homepage'));
    }
}
