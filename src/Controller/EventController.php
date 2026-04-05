<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/events')]
class EventController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(EventRepository $repo): Response
    {
        return $this->json($repo->findAll());
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $data = json_decode($request->getContent(), true);

        // 🔴 Validation
        if (!isset(
            $data['title'],
            $data['description'],
            $data['date'],
            $data['location'],
            $data['seats'],
            $data['image']
        )) {
            return $this->json(['error' => 'Missing fields'], 400);
        }

        $event = new Event();
        $event->setTitle($data['title']);
        $event->setDescription($data['description']);
        $event->setDate(new \DateTime($data['date']));
        $event->setLocation($data['location']);
        $event->setSeats((int) $data['seats']);
        $event->setImage($data['image']);

        $em->persist($event);
        $em->flush();

        return $this->json($event, 201);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Event $event): Response
    {
        return $this->json($event);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Request $request, Event $event, EntityManagerInterface $em): Response
    {
        $data = json_decode($request->getContent(), true);

        // 🟡 Update flexible (pas obligatoire tout envoyer)
        if (isset($data['title'])) {
            $event->setTitle($data['title']);
        }

        if (isset($data['description'])) {
            $event->setDescription($data['description']);
        }

        if (isset($data['date'])) {
            $event->setDate(new \DateTime($data['date']));
        }

        if (isset($data['location'])) {
            $event->setLocation($data['location']);
        }

        if (isset($data['seats'])) {
            $event->setSeats((int) $data['seats']);
        }

        if (isset($data['image'])) {
            $event->setImage($data['image']);
        }

        $em->flush();

        return $this->json($event);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(Event $event, EntityManagerInterface $em): Response
    {
        $em->remove($event);
        $em->flush();

        return $this->json(['message' => 'Deleted']);
    }
}