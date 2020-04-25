<?php

namespace App\Tests;

use DateTime;
use App\Entity\Event;

trait EventFactory
{
    private function createEvent(array $overrides = [])
    {
        $data = array_merge([
            'name'        => 'Super Conference',
            'location'    => 'London, UK',
            'price'       => 14,
            'description' => 'Best Super Conférence Ever!',
            'startsAt'    => new DateTime('+25 days')
        ], $overrides);

        $event = new Event($data);

        $this->em->persist($event);
        $this->em->flush();

        return $event;
    }
}