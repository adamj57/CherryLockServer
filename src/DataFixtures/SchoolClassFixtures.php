<?php

namespace App\DataFixtures;

use App\Entity\SchoolClass;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class SchoolClassFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $mech = [2, 2, 3];
        $lic = [2, 2];
        $inf = [3, 3, 3];
        $labels = ["a", "b", "c"];

        $i = 1;
        foreach ($mech as $number) {
            for ($j = 0; $j < $number; $j++){
                $class = new SchoolClass();
                $class->setYear($i);
                $class->setShortname("tm".strval($j + 1));
                $class->setSchoolClassType($this->getReference(SchoolClassTypeFixtures::MECH));
                $manager->persist($class);
            }
            $i++;
        }

        $i = 1;
        foreach ($lic as $number) {
            for ($j = 0; $j < $number; $j++){
                $class = new SchoolClass();
                $class->setYear($i);
                $class->setShortname("l".$labels[$j]);
                $class->setSchoolClassType($this->getReference(SchoolClassTypeFixtures::LIC));
                $manager->persist($class);
            }
            $i++;
        }

        $i = 1;
        foreach ($inf as $number) {
            for ($j = 0; $j < $number; $j++){
                $class = new SchoolClass();
                $class->setYear($i);
                $class->setShortname("t".$labels[$j]);
                $class->setSchoolClassType($this->getReference(SchoolClassTypeFixtures::INF));
                $manager->persist($class);
            }
            $i++;
        }

        $manager->flush();
    }

    public function getDependencies(){
        return array(
            SchoolClassTypeFixtures::class
        );
    }
}
