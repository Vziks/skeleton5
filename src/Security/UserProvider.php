<?php

namespace App\Security;

use App\Entity\UserAdmin;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function loadUserByUsername($username): UserAdmin
    {
        $user = $this->findOneUserBy(['email' => $username]);

        if (!$user) {
            throw new UserNotFoundException(sprintf('User with "%s" email does not exist.', $username));
        }

        return $user;
    }

    public function refreshUser(UserInterface $user): UserAdmin
    {
        \assert($user instanceof UserAdmin);

        if (null === $reloadedUser = $this->findOneUserBy(['id' => $user->getId()])) {
            throw new UserNotFoundException(sprintf('User with ID "%s" could not be reloaded.', $user->getId()));
        }

        return $reloadedUser;
    }

    public function supportsClass($class): bool
    {
        return UserAdmin::class === $class;
    }

    /**
     * @throws NonUniqueResultException
     */
    public function loadUserByIdentifier(string $identifier): ?UserAdmin
    {
        return $this->entityManager->createQuery(
            'SELECT u
                FROM App\Entity\User u
                WHERE u.username = :query
                OR u.email = :query'
        )
            ->setParameter('query', $identifier)
            ->getOneOrNullResult()
        ;
    }

    private function findOneUserBy(array $options): ?UserAdmin
    {
        return $this->entityManager
            ->getRepository(UserAdmin::class)
            ->findOneBy($options)
        ;
    }
}
