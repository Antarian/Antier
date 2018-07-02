<?php
namespace App\Security;

use App\Model\UserModel;
use App\Service\MongoService;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class UserProvider implements UserProviderInterface
{
    protected $service;

    public function __construct(MongoService $service)
    {
        $this->service = $service;
    }

    public function loadUserByUsername($username)
    {
        $userModel = $this->service->findUserByUsername($username);

        if ($userModel) {
            return $userModel;
        }

        throw new UsernameNotFoundException(
            sprintf('Username "%s" does not exist.', $username)
        );
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof UserModel) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return UserModel::class === $class;
    }
}
