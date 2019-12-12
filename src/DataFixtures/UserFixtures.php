<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $userAdmin = new User();
        $userAdmin->setEmail('administratorius@imone.lt');
        $userAdmin->setRoles([User::ROLE_ADMIN]);
        $userAdmin->setPassword($this->passwordEncoder->encodePassword(
            $userAdmin,
            'password'
        ));

        $manager->persist($userAdmin);
        $manager->flush();

        $this->addReference('user-admin', $userAdmin);

        $userSuperAdmin = new User();
        $userSuperAdmin->setEmail('administratorius@imone.ltu');
        $userSuperAdmin->setRoles([User::ROLE_ADMIN]);
        $userSuperAdmin->setPassword($this->passwordEncoder->encodePassword(
            $userSuperAdmin,
            'passwordu'
        ));

        $manager->persist($userSuperAdmin);
        $manager->flush();

        $this->addReference('user-super-admin', $userSuperAdmin);

        $userManager = new User();
        $userManager->setEmail('vadybininkas@imone.lt');
        $userManager->setRoles([User::ROLE_MANAGER]);
        $userManager->setPassword($this->passwordEncoder->encodePassword(
            $userManager,
            'password'
        ));

        $manager->persist($userManager);
        $manager->flush();

        $this->addReference('user-manager', $userManager);
    }
}
