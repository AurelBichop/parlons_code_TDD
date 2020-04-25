<?php

namespace App\Tests\Controller;

use DateTime;
use App\Entity\Event;
use App\Tests\EventFactory;
use Cake\Utility\Text;
use App\Tests\Framework\WebTestCase;


class EventsControllerTest extends WebTestCase
{
    use EventFactory;

    /** @test */
    public function index_should_list_only_upcoming_events()
    {
        //dd(mb_substr('Event lol', 3));
        $event1 = $this->createEvent([
            'name'        => 'Symfony Conference',
            'location'    => 'Paris, FR',
            'price'       => 0,
            'description' => 'Best Symfony Conférence Ever!',
            'startsAt'    => new DateTime('+15 days')
        ]);

        $event2 = $this->createEvent([
            'name'        => 'Laravel Conference',
            'location'    => 'Quebec, CA',
            'price'       => 25,
            'description' => 'Best Lavarel Conférence Ever!',
            'startsAt'    => new DateTime('+10 days')
        ]);

        $event3 = $this->createEvent([
            'name'        => 'Django Conference',
            'location'    => 'Dakar, SN',
            'price'       => 12,
            'description' => 'Best Django Conférence Ever!',
            'startsAt'    => new DateTime('-1 month')
        ]);


        // $this->crawler = $this->client->request('GET', '/events');

        // $responseContent = $this->client->getResponse()->getContent();

        // $this->assertResponseIsSuccessful();
        // $this->assertStringContainsString('3 Events', $responseContent);
        // $this->assertStringContainsString($event1->getName(), $responseContent);
        // $this->assertStringContainsString($event2->getName(), $responseContent);
        // $this->assertStringContainsString($event3->getName(), $responseContent);

        $this->visit('/events')
            //->dump()
            ->assertResponseOk()
            ->seeText('2 Events')
            ->seeText($event1->getName())
            ->seeText(
                Text::truncate($event1->getDescription(), Event::DESCRIPTION_TRUNCATE_LIMIT, ['ellipsis' => '...', 'exact' => true, 'html' => true])
            )
            ->seeText($event1->getLocation())
            ->seeText('FREE!')
            ->seeText($event1->getStartsAt()->format($this->getParameter('app.default_date_format')))

            ->seeText($event2->getName())
            ->seeText(
                Text::truncate($event2->getDescription(), Event::DESCRIPTION_TRUNCATE_LIMIT, ['ellipsis' => '...', 'exact' => true, 'html' => true])
            )
            ->seeText($event2->getLocation())
            ->seeText('$25')
            ->seeText($event2->getStartsAt()->format($this->getParameter('app.default_date_format')))

            ->dontSeeText($event3->getName())
            ->dontSeeText(
                Text::truncate($event3->getDescription(), Event::DESCRIPTION_TRUNCATE_LIMIT, ['ellipsis' => '...', 'exact' => true, 'html' => true])
                )
            ->dontSeeText($event3->getLocation())
            ->dontSeeText('$12')
            ->dontSeeText($event3->getStartsAt()->format($this->getParameter('app.default_date_format')));
    }

    /** @test */
    public function index_should_list_the_rigth_number_of_events()
    {
        for($i = 1; $i <= 10; $i++)
        {
            $this->createEvent(['startsAt' => new DateTime('+20 days')]);
        }

        $this->visit('/events')
            ->assertResponseOk()
            ->assertCount(Event::NUM_ITEMS, $this->filter('article.event'));
    }

    /** @test */
    public function index_should_list_only_upcoming_events_ordered_by_ascending_starts_at()
    {
        $event3 = $this->createEvent(['name'=>'Event 3','startsAt' => new DateTime('+3 months')]);
        $event1 = $this->createEvent(['name' => 'Event 1','startsAt' => new DateTime('+15 days')]);
        $event2 = $this->createEvent(['name' => 'Event 2','startsAt' => new DateTime('+28 days')]);

        $this->visit('/events')
            ->assertResponseOk()
            ->assertElementTextContains('Event 1', $this->filter('article.event')->eq(0))
            ->assertElementTextContains('Event 2', $this->filter('article.event')->eq(1))
            ->assertElementTextContains('Event 3', $this->filter('article.event')->eq(2));
    }

    /** @test */
    public function index_should_properly_paginate_events()
    {
        for ($i = 1; $i <= 10; $i++) {
            $this->createEvent(['startsAt' => new DateTime('+20 days')]);
        }

        $this->visit('/events?page=1')
            ->assertResponseOk()
            ->assertCount(3, $this->crawler->filter('article.event'));

        $this->visit('/events?page=2')
            ->assertResponseOk()
            ->assertCount(3, $this->crawler->filter('article.event'));

        $this->visit('/events?page=3')
            ->assertResponseOk()
            ->assertCount(3, $this->crawler->filter('article.event'));

        $this->visit('/events?page=4')
            ->assertResponseOk()
            ->assertCount(1, $this->crawler->filter('article.event'));    
    }

    /** @test */
    public function show_should_list_the_event_details()
    {
        //Arrange
        $event = $this->createEvent(['price' => 25]);

        //Act
        $this->visit('/events/' . $event->getId())

            //Assertion
            ->assertResponseOk()
            ->seeText($event->getName())
            ->seeText($event->getDescription())
            ->seeText($event->getLocation())
            ->seeText('$25')
            ->seeText($event->getStartsAt()->format($this->getParameter('app.default_date_format')));
    }

    /** @test */
    public function show_should_return_a_404_response_if_we_cant_find_an_event_with_the_specified_id()
    {
        //Arrange
        $event1 = $this->createEvent(['price' => 25]);


        //Act
        $this->visit('/events/' . $event1->getId())
        
        //Assertion
            ->assertResponseOk();

        $this->visit('/events/2000')
            ->seeStatusCode(404);    


    }

    /** @test */
    public function navigation_from_show_page_to_index_page_should_work()
    {
        //Arrange
        $event = $this->createEvent();


        //Act
        $this->visit('/events/' . $event->getId())
            ->assertResponseOk()
            ->clickLink('All Events')
            ->seePageIs('/events');
    }

    /** @test */
    public function navigation_from_index_page_to_show_page_should_work()
    {
        //Arrange
        $event1 = $this->createEvent(['name' => 'Symfony Conference']);
        $event2 = $this->createEvent(['name' => 'Laravel Conference']);


        //Act
        $this->visit('/events')
            ->assertResponseOk()
            ->clickLink($event1->getName())
            ->seePageIs('/events/' . $event1->getId());

        $this->visit('/events')
            ->assertResponseOk()
            ->clickLink($event2->getName())
            ->seePageIs('/events/' . $event2->getId());
    }

    /** @test */
    public function the_event_price_should_be_displayed_if_the_price_is_neither_zero_or_null()
    {
        //Arrange
        $event = $this->createEvent(['price' => 25]);

        //Act
        $this->visit('/events/' . $event->getId())
            ->assertResponseOk()
            ->seeText('$25');
    }

    /** @test */
    public function free_should_be_displayed_if_the_price_is_zero()
    {
        $event = $this->createEvent(['price' => 0]);
        
       
        $this->visit('/events/' . $event->getId())
            ->assertResponseOk()
            ->seeText('FREE!');            
    }

    /** @test */
    public function free_should_be_displayed_if_the_price_is_null()
    {
        $event = $this->createEvent(['price' => null]);

        $this->visit('/events/' . $event->getId())
            ->assertResponseOk()
            ->seeText('FREE!');
    }
}
