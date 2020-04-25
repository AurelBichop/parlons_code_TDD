<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Event;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;



class AppFixtures extends Fixture
{

    private $faker; 


    public function load(ObjectManager $manager)
    {
        $this->faker = Factory::create();

        $this->addEvents($manager);

        $manager->flush();
    }

    private function addEvents(ObjectManager $manager){

        for ($i = 0; $i <= 10; $i++) {
            $event = new Event([
                'name'        => $name = $this->faker->unique()->randomElement($this->eventNames()),
                'location'    => $this->faker->city(),
                'price'       => mt_rand(1, 10) > 5 ? $this->faker->numberBetween(15, 100) : 0,
                'description' => 'This is the best **' . $name . '** ever !',
                'startsAt'    => mt_rand(0,10) > 2 ? $this->faker->dateTimeBetween(
                    '+10 days', '+100 days'):$this->faker->dateTimeBetween('-10 days, -5 days')
            ]);


            $manager->persist($event);
        }
    }


    private function eventNames():array
    {
        return [
            'Symfony Conférence',
            'Lavarel Conférence',
            'Django Conférence',
            'Python Conférence',
            'Java Conférence',
            'Spring Conférence',
            'Flash Conférence',
            'Node.js Conférence',
            'C++ Conférence',
            'Javascript Conférence',
            'Php Conférence',
        ];
    }
}
