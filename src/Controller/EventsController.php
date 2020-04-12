<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EventsController extends AbstractController
{
    /**
     * @Route("/", name="home", methods={"GET"})
     * @Route("/events", name="events.index", methods={"GET"})
     */
    public function index(EventRepository $repo, Request $request)
    {

        //$descriptionTruncateLimit = $this->getParameter('app.description_truncate_limit');
        //return $this->json(['status' => true, 'message' => 'hello from my API']);
       
        $paginator = $repo->getUpcomingOrderedByAscStartsAtPaginator(
            $request->query->getInt('page', 1)
        );

        return $this->render('events/index.html.twig', ['paginator'=> $paginator]);
    }

    /**
     * @Route("/events/{id<\d+>}", name="events.show", methods={"GET"})
     */
    public function show(Event $event)
    {
        return $this->render('events/show.html.twig', compact('event'));
    }
}
