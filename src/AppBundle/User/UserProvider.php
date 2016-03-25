<?php

namespace AppBundle\User;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use AppBundle\Entity\User;

class UserProvider implements UserProviderInterface
{
	private $repository;

	public function __construct($repository)
	{
		$this->repository = $repository;
	}

	public function loadUserByUsername($username)
	{
		$user = $this->repository->findOneBy(['username'=>$username]);

        if (null === $user) {
            $message = sprintf(
                'Unable to find an ApiBundle:User object identified by "%s".',
                $username
            );
            throw new UsernameNotFoundException($message);
        }
        return $user;
	}

	public function refreshUser(UserInterface $user)
	{
		$class = get_class($user);

        if (!$this->supportsClass($class))
        {
            throw new UnsupportedUserException(sprintf(
                'Instances of "%s" are not supported.',
                $class
            ));
        }

        $username = $user->getUsername();

        return $this->loadUserByUsername($username);
	}

	public function supportsClass($class)
	{
		return $class instanceof User;
	}
}