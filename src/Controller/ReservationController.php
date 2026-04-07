<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Reservations;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/reservations')]
class ReservationController extends AbstractController
{
    // ===============================================
    // 1️⃣ Créer une réservation
    // ===============================================
    #[Route('', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $em,
        EventRepository $eventRepo
    ): Response {
        $data = json_decode($request->getContent(), true);

        // 🔴 Validation des champs
        if (!isset($data['event_id'], $data['name'], $data['email'], $data['phone'])) {
            return $this->json(['error' => 'Missing fields'], 400);
        }

        // 🔹 Récupérer l'événement
        $event = $eventRepo->find($data['event_id']);
        if (!$event) {
            return $this->json(['error' => 'Event not found'], 404);
        }

        // 🔹 Vérifier les places disponibles
        if ($event->getSeats() <= 0) {
            return $this->json(['error' => 'No seats available'], 400);
        }

        // 🔹 Créer la réservation
        $reservation = new Reservations();
        $reservation->setName($data['name']);
        $reservation->setEmail($data['email']);
        $reservation->setPhone($data['phone']);
        $reservation->setCreatedAt(new \DateTimeImmutable());
        $reservation->setEventId($event); // ⚡ utiliser event_id comme propriété

        // 🔹 Décrémenter les places
        $event->setSeats($event->getSeats() - 1);

        $em->persist($reservation);
        $em->flush();

        return $this->json(['message' => 'Reservation confirmed'], 201);
    }


    #[Route('/event/{id}', methods: ['GET'])]
    public function byEvent(int $id, EventRepository $eventRepo): Response
    {
        $event = $eventRepo->find($id);
        if (!$event) {
            return $this->json(['error' => 'Event not found'], 404);
        }

        $reservations = $event->getReservations();

        // 🔹 Transformer la collection en tableau simple
        $data = [];
        foreach ($reservations as $r) {
            $data[] = [
                'id' => $r->getId(),
                'name' => $r->getName(),
                'email' => $r->getEmail(),
                'phone' => $r->getPhone(),
                'created_at' => $r->getCreatedAt()->format('Y-m-d H:i:s'),
            ];
        }

        return $this->json($data);
    }
}
