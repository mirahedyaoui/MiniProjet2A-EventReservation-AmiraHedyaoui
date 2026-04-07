<?php
// src/Controller/UserController.php
namespace App\Controller;

use App\Entity\Event;
use App\Entity\Reservations;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/login', name: 'user_login', methods: ['GET', 'POST'])]
    public function login(Request $request): Response
    {
        // Ici tu peux ajouter POST pour authentification réelle
        return $this->render('user/login.html.twig');
    }

    #[Route('/events', name: 'user_events')]
    public function listEvents(EventRepository $repo): Response
    {
        $events = $repo->findAll();
        return $this->render('user/events.html.twig', [
            'events' => $events
        ]);
    }

    #[Route('/event/{id}', name: 'user_event_detail')]
    public function eventDetail(Event $event): Response
    {
        return $this->render('user/event_detail.html.twig', [
            'event' => $event
        ]);
    }

    #[Route('/event/{id}/reserve', name: 'user_reserve', methods: ['POST'])]
    public function reserve(
        Request $request,
        Event $event,
        EntityManagerInterface $em
    ): Response {
        $data = $request->request->all();

        if (empty($data['name']) || empty($data['email']) || empty($data['phone'])) {
            $this->addFlash('error', 'Please fill all fields');
            return $this->redirectToRoute('user_event_detail', ['id' => $event->getId()]);
        }

        $reservation = new Reservations();
        $reservation->setName($data['name']);
        $reservation->setEmail($data['email']);
        $reservation->setPhone($data['phone']);
        $reservation->setCreatedAt(new \DateTimeImmutable());
        $reservation->setEventId($event);

        // Décrémente le nombre de places
        $event->setSeats($event->getSeats() - 1);

        $em->persist($reservation);
        $em->flush();

        $this->addFlash('success', 'Reservation confirmed!');
        return $this->redirectToRoute('user_event_detail', ['id' => $event->getId()]);
    }
}