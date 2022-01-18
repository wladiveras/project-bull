<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\Customer;
use App\Entity\Bull;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $role = ['ADMIN', 'USER', 'MOD', 'VIP'];
        $status = ['alive', 'dead'];

        for ($i = 0; $i < 10; $i++) {
            $customer = new Customer();
            $customer->setName($faker->name);
            $customer->setEmail($faker->email);
            $customer->setRole($role[mt_rand(0, 3)]);
            $customer->setAge(mt_rand(18, 100));
            $manager->persist($customer);
        }


        for ($i = 0; $i < 5500; $i++) {
            $int = mt_rand(1262055681, 2262055681);
            $datetime = date("Y-m-d H:i:s", $int);

            $bull = new Bull();
            $bull->setCode(abs(crc32(uniqid())));
            $bull->setWeight(mt_rand(200, 1000));
            $bull->setBirthday($datetime);
            $bull->setWeekMilk(mt_rand(0, 100));
            $bull->setWeekFood(mt_rand(0, 60));
            $bull->setStatus($status[mt_rand(0, 1)]);
            $manager->persist($bull);
        }

        $manager->flush();
    }
}
