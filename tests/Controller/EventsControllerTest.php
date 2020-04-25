<?php

namespace App\Tests\Controller;

use DateTime;
use App\Entity\Event;
use App\Tests\Framework\WebTestCase;


class EventsControllerTest extends WebTestCase
{
    private function createEvent(array $overrides = [])
    {

        $data = array_merge([
            'name'        => 'Super Conference',
            'location'    => 'London, UK',
            'price'       => 14,
            'description' => 'Best Super Conférence Ever!',
            'startsAt'    => new DateTime('+25 days')
        ],$overrides);

        $event = new Event($data);

        $this->em->persist($event);
        $this->em->flush();

        return $event;
    }
    /** @test */
    public function index_should_list_all_events()
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
            'startsAt'    => new DateTime('+1 month')
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
            ->seeText('3 Events')
            ->seeText($event1->getName())
            ->seeText(mb_substr($event1->getDescription(), 0, Event::DESCRIPTION_TRUNCATE_LIMIT))
            ->dontSeeText(mb_substr($event1->getDescription(), Event::DESCRIPTION_TRUNCATE_LIMIT))
            ->seeText($event1->getLocation())
            ->seeText('FREE!')
            ->seeText($event1->getStartsAt()->format($this->getParameter('app.default_date_format')))
            ->seeText($event2->getName())
            ->seeText(mb_substr($event2->getDescription(), 0, Event::DESCRIPTION_TRUNCATE_LIMIT))
            ->dontSeeText(mb_substr($event2->getDescription(), Event::DESCRIPTION_TRUNCATE_LIMIT))
            ->seeText($event2->getLocation())
            ->seeText('$25')
            ->seeText($event2->getStartsAt()->format($this->getParameter('app.default_date_format')))
            ->seeText($event3->getName())
            ->seeText(mb_substr($event3->getDescription(), 0, Event::DESCRIPTION_TRUNCATE_LIMIT))
            ->dontSeeText(mb_substr($event3->getDescription(), Event::DESCRIPTION_TRUNCATE_LIMIT))
            ->seeText($event3->getLocation())
            ->seeText('$12')
            ->seeText($event3->getStartsAt()->format($this->getParameter('app.default_date_format')));
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
