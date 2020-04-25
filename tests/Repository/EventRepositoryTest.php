<?php

namespace App\Tests\Repository;

use DateTime;
use App\Entity\Event;
use App\Tests\EventFactory;
use App\Tests\Framework\KernelTestCase;


class EventRepositoryTest extends KernelTestCase
{
    use EventFactory;

    private $eventRepository;

    protected function setUp(): void
    {
        parent::setUP();
        $this->eventRepository = $this->em->getRepository(Event::class);
    }


    /** @test */
    public function getUpcomingOrderedByAscStartsAtPaginator_should_return_only_upcoming_events()
    {
        //Arrange
        $this->createEvent(['startsAt' => new DateTime('+10 days')]);
        $this->createEvent(['startsAt' => new DateTime('+1 months')]);
        $this->createEvent(['startsAt' => new DateTime('-20 days')]);

        //Act
        $results = $this->eventRepository->getUpcomingOrderedByAscStartsAtPaginator();

        //Assert
        $this->assertCount(2,$results);
    }


    /** @test */
    public function getUpcomingOrderedByAscStartsAtPaginator_should_return_rigth_number_of_events()
    {
        //Arrange
        for($i = 1; $i <= 10; $i++){
            $this->createEvent(['startsAt' => new DateTime('+10 days')]);
        }
        
        //Act
        $results = $this->eventRepository->getUpcomingOrderedByAscStartsAtPaginator(1);

        //Assert
        $this->assertCount(Event::NUM_ITEMS, $results->getCurrentPageResults());
    }


    /** @test */
    public function getUpcomingOrderedByAscStartsAtPaginator_should_properly_paginate_events()
    {
        //Arrange
        for ($i = 1; $i <= 10; $i++) {
            $this->createEvent(['startsAt' => new DateTime('+10 days')]);
        }

        //Act
        $paginator = $this->eventRepository->getUpcomingOrderedByAscStartsAtPaginator();

        //Assert
        $this->assertSame(4, $paginator->getNbPages());
        $this->assertCount(3, $this->eventRepository->getUpcomingOrderedByAscStartsAtPaginator(1)->getCurrentPageResults());
        $this->assertCount(3, $this->eventRepository->getUpcomingOrderedByAscStartsAtPaginator(2)->getCurrentPageResults());
        $this->assertCount(3, $this->eventRepository->getUpcomingOrderedByAscStartsAtPaginator(3)->getCurrentPageResults());
        $this->assertCount(1, $this->eventRepository->getUpcomingOrderedByAscStartsAtPaginator(4)->getCurrentPageResults());
    }

    /** @test */
    public function getUpcomingOrderedByAscStartsAtPaginator_should_return_only_ordred_by_asc_starts_at()
    {
        //Arrange
        $this->createEvent(['name'=> 'Event 3','startsAt' => new DateTime('+2 months')]);
        $this->createEvent(['name' => 'Event 1','startsAt' => new DateTime('+5 days')]);
        $this->createEvent(['name' => 'Event 2','startsAt' => new DateTime('+10 days')]);

        //Act
        $paginator = $this->eventRepository->getUpcomingOrderedByAscStartsAtPaginator();

        //Assert
        $results = $paginator->getCurrentPageResults();

        $this->assertSame('Event 1',$results[0]->getName());
        $this->assertSame('Event 2', $results[1]->getName());
        $this->assertSame('Event 3', $results[2]->getName());
        
    }
}