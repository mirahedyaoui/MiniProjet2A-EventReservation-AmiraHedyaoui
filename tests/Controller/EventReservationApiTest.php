<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Event;
use App\Entity\Reservations;
use Doctrine\ORM\EntityManagerInterface;

class EventReservationApiTest extends WebTestCase
{
    private $client;
    private $em;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
        
    }
    
    public function testGetAllEvents(): void
    {
        $this->client->request('GET', '/api/events');
        $this->assertResponseIsSuccessful();
        $events = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertIsArray($events);
    }

    public function testCreateReservation(): void
    {
        // Prendre un event existant
        $event = $this->em->getRepository(Event::class)->findOneBy([]);
        $this->assertNotNull($event, "Il faut au moins un événement pour ce test");

        $payload = [
            'event_id' => $event->getId(),
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'phone' => '12345678',
        ];

        $this->client->request(
            'POST',
            '/api/reservations',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($payload)
        );

        $this->assertResponseStatusCodeSame(201);
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('message', $response);
        $this->assertEquals('Reservation confirmed', $response['message']);
    }

    public function testGetReservationsByEvent(): void
    {
        $event = $this->em->getRepository(Event::class)->findOneBy([]);
        $this->assertNotNull($event);

        $this->client->request('GET', '/api/reservations/event/'.$event->getId());
        $this->assertResponseIsSuccessful();
        $reservations = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertIsArray($reservations);
    }
}