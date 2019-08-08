<?php

namespace App\DataFixtures;

use App\Entity\SchoolClassType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class SchoolClassTypeFixtures extends Fixture
{

    public const INF = "inf";
    public const MECH = "mech";
    public const LIC = "lic";

    public function load(ObjectManager $manager)
    {
        $inf = new SchoolClassType();
        $inf->setName("Informatyk");
        $manager->persist($inf);

        $mech = new SchoolClassType();
        $mech->setName("Mechatronik");
        $manager->persist($mech);

        $lic = new SchoolClassType();
        $lic->setName("Liceum");
        $manager->persist($lic);

        $manager->flush();

        $this->addReference(self::INF, $inf);
        $this->addReference(self::MECH, $mech);
        $this->addReference(self::LIC, $lic);
    }
}
