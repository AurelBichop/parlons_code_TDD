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


    /**
     * @test 
     * @dataProvider pluralizeProvider
    */
    
    public function pluralize_should_work($expected, $count, $singular, $plural = null)
    {
        $appExtension = new AppExtension;

        $this->assertSame($expected, $appExtension->pluralize($count, $singular,$plural));
    }

    public function pluralizeProvider()
    {
        return [
            // Without plural form provided
            ['0 Events', 0, 'Event'],
            ['1 Event', 1, 'Event'],
            ['45 Events', 45, 'Event'],

            // With plural form provided
            ['0 people', 0, 'person', 'people'],
            ['1 person', 1, 'person', 'people'],
            ['2 people', 2, 'person', 'people'],
            ['32 people', 32, 'person', 'people']
        ];
    }

    /** @test */
    public function pluralize_should_raise_an_exception_if_the_count_is_invalid()
    {
        $invalidCount = "WRONG_VALUE";

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("{$invalidCount} must be a numeric value");

        $appExtension = new AppExtension;

        $this->assertSame('0 people', $appExtension->pluralize($invalidCount, 'person', 'people'));
    }
}
