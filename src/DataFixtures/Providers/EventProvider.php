<?php

namespace App\DataFixtures\Providers;

use Faker\Provider\Base as BaseProvider;

final class EventProvider extends BaseProvider
{

    public CONST NAMES = [
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

    public function eventName():string
    {
        return $this->unique()->randomElement(self::NAMES);
    }
}