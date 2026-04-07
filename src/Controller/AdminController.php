<?php
// src/Controller/AdminController.php
namespace App\Controller;

use App\Entity\Event;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    #[Route('/admin/login', name: 'admin_login', methods: ['GET', 'POST'])]
    public function login(): Response
    {
        return $this->render('admin/login.html.twig');
    }

    #[Route('/admin/dashboard', name: 'admin_dashboard')]
    public function dashboard(EventRepository $repo): Response
    {
        $events = $repo->findAll();
        return $this->render('admin/dashboard.html.twig', [
            'events' => $events
        ]);
    }

    #[Route('/admin/event/create', name: 'admin_event_create', methods: ['GET','POST'])]
    public function createEvent(Request $request, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) {
            $data = $request->request->all();

            $event = new Event();
            $event->setTitle($data['title']);
            $event->setDescription($data['description']);
            $event->setDate(new \DateTime($data['date']));
            $event->setLocation($data['location']);
            $event->setSeats((int)$data['seats']);
            $event->setImage($data['image']);

            $em->persist($event);
            $em->flush();

            $this->addFlash('success', 'Event created!');
            return $this->redirectToRoute('admin_dashboard');
        }

        return $this->render('admin/event_create.html.twig');
    }

    #[Route('/admin/event/{id}/edit', name: 'admin_event_edit', methods: ['GET','POST'])]
    public function editEvent(Event $event, Request $request, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) {
            $data = $request->request->all();
            $event->setTitle($data['title']);
            $event->setDescription($data['description']);
            $event->setDate(new \DateTime($data['date']));
            $event->setLocation($data['location']);
            $event->setSeats((int)$data['seats']);
            $event->setImage($data['image']);

            $em->flush();

            $this->addFlash('success', 'Event updated!');
            return $this->redirectToRoute('admin_dashboard');
        }

        return $this->render('admin/event_edit.html.twig', ['event' => $event]);
    }

    #[Route('/admin/event/{id}/delete', name: 'admin_event_delete', methods: ['POST'])]
    public function deleteEvent(Event $event, EntityManagerInterface $em): Response
    {
        $em->remove($event);
        $em->flush();

        $this->addFlash('success', 'Event deleted!');
        return $this->redirectToRoute('admin_dashboard');
    }
    #[Route('/admin/reservations', name: 'admin_reservations')]
public function reservations(EventRepository $eventRepo): Response
{
    $events = $eventRepo->findAll();

    return $this->render('admin/reservations.html.twig', [
        'events' => $events,
    ]);
}
}