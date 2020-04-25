<?php

namespace App\Tests\Twig;

use App\Entity\Event;
use App\Twig\AppExtension;
use PHPUnit\Framework\TestCase;

class AppExtensionTest extends TestCase
{


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
