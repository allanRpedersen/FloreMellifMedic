<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
	private $encoder;

	public function __construct(UserPasswordEncoderInterface $encoder){

		$this->encoder = $encoder;
	}

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
		// $manager->persist($product);
		

		$adminRole = new Role();
		$adminRole->setTitle('ROLE_ADMIN');
		$manager->persist($adminRole);

		$adminUser = new User();
		$adminUser->setEmail('elisee.reclus@webcoop.fr');
		$adminUser->setPassword($this->encoder->encodePassword($adminUser, 'password'));
		$adminUser->addUserRole($adminRole);

		$manager->persist($adminUser);

        $manager->flush();
    }
}
