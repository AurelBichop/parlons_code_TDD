<?php

namespace App\Tests\Entity;

use App\Entity\Event;
use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    /** 
     * @test
     * @dataProvider eventWithPrice
    */
    public function an_event_should_not_be_free($event)
    {
        $this->assertFalse($event->isFree());
    }

    public function eventWithPrice()
    {
        return [
            [new Event(['price' => 12])],
            [new Event(['price' => 45.99])]
        ];
    }

    /**
     * @test 
     * @dataProvider eventWithoutPrice
     */
    public function an_event_should_be_free_if_the_price_is_null_or_zero($event)
    {
        $this->assertTrue($event->isFree());
    }

    public function eventWithoutPrice()
    {
        return [
            [new Event(['price' => 0])],
            [new Event(['price' => null])]
        ];
    }
}