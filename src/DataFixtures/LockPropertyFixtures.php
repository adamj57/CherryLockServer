<?php

namespace App\DataFixtures;

use App\Entity\LockProperty;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LockPropertyFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $opened = new LockProperty();
        $opened->setName("opened")
            ->setType(LockProperty::BOOL)
            ->setValue(false);
        $manager->persist($opened);

        $opened_perm = new LockProperty();
        $opened_perm->setName("opened_perm")
            ->setType(LockProperty::BOOL)
            ->setValue(false);
        $manager->persist($opened_perm);

        $token = new LockProperty();
        $token->setName("token")
            ->setType(LockProperty::STRING)
            ->setValue("CHANGE-ME");
        $manager->persist($token);

        $manager->flush();
    }
}
