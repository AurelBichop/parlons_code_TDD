<?php

namespace App\Tests\Twig;

use App\Entity\Event;
use App\Twig\AppExtension;
use PHPUnit\Framework\TestCase;

class AppExtensionTest extends TestCase
{
    /** @test */
    public function format_price_should_work()
    {
        $event1 = new Event(['price'=> 0]);
        $event2 = new Event(['price' => null]);
        $event3 = new Event(['price' => 9.5]);
        $event4 = new Event(['price' => 45.99]);

        $appExtension = new AppExtension;

        
        $this->assertSame('<strong>FREE!</strong>', $appExtension->formatPrice($event1));
        $this->assertSame('<strong>FREE!</strong>', $appExtension->formatPrice($event2));
        $this->assertSame('$9.5', $appExtension->formatPrice($event3));
        $this->assertSame('$45.99', $appExtension->formatPrice($event4));
    }
}
