<?php

namespace App\DataFixtures;

use App\Entity\UserAdmin;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserAdminFixture extends Fixture
{
    private UserPasswordHasherInterface $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        $moderator = $this->getFakeAdmin();
        $manager->persist($moderator);

        $manager->flush();
    }

    public function getFakeAdmin(): UserAdmin
    {
        $user = new UserAdmin();

        return $user
            ->setEmail('admin@symfony.com')
            ->setPassword($this->encoder->hashPassword($user, 'admin'))
            ->setRoles([
                'ROLE_SONATA_ADMIN',
                'ROLE_ADMIN',
            ])
        ;
    }

    /**
     * Get the order of this fixture.
     *
     * @return int
     */
    public function getOrder()
    {
        return 0;
    }
}
