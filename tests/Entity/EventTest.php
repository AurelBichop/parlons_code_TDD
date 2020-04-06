<?php

namespace App\Tests\Entity;

use App\Entity\Event;
use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    /** @test */
    public function an_event_should_not_be_free_if_the_price_is_neither_zero_or_null()
    {
        $event1 = new Event(['price'=> 12]);
        $event2 = new Event(['price' => 45.99]);

        $this->assertFalse($event1->isFree());
        $this->assertFalse($event2->isFree());
    }

    /** @test */
    public function an_event_should_be_free_if_the_price_is_zero()
    {
        $event = new Event(['price' => 0]);

        $this->assertTrue($event->isFree());
    }

    /** @test */
    public function an_event_should_be_free_if_the_price_is_null()
    {
        $event = new Event(['price' => null]);

        $this->assertTrue($event->isFree());
    }
}
