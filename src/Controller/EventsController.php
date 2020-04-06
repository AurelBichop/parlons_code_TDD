<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class EventsController extends AbstractController
{
    /**
     * @Route("/events", name="events.index", methods={"GET"})
     */
    public function index(EventRepository $repo)
    {
        //$descriptionTruncateLimit = $this->getParameter('app.description_truncate_limit');
        //return $this->json(['status' => true, 'message' => 'hello from my API']);
        $events = $repo->findAll();
        return $this->render('events/index.html.twig', compact('events'));
    }

    /**
     * @Route("/events/{id<\d+>}", name="events.show", methods={"GET"})
     */
    public function show(Event $event)
    {
        return $this->render('events/show.html.twig', compact('event'));
    }
}
