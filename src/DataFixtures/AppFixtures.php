<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Event;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        // 1. Création de l'Admin pour le projet (indispensable pour JWT)
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setRoles(['ROLE_ADMIN']);
        // On hache le mot de passe "admin123"
        $password = $this->hasher->hashPassword($admin, 'admin123');
        $admin->setPassword($password);
        $manager->persist($admin);

        // 2. Création de quelques événements de test
        for ($i = 1; $i <= 5; $i++) {
            $event = new Event();
            $event->setTitle("Événement ISSAT $i");
            $event->setDescription("Description détaillée de l'événement $i");
            $event->setDate(new \DateTime("+ $i days"));
            $event->setLocation("Sousse");
            $event->setSeats(50);
            $event->setImage("https://via.placeholder.com/150");
            $manager->persist($event);
        }

        $manager->flush();
    }
}
