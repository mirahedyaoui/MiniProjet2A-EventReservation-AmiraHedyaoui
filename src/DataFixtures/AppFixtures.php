<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Event;
use App\Entity\WebauthnCredential;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Webauthn\PublicKeyCredentialSource;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        // 1️⃣ Création de l'admin
        $admin = new User('admin@example.com'); // email utilisé comme identifiant
        $admin->setRoles(['ROLE_ADMIN']);
        $password = $this->hasher->hashPassword($admin, 'admin123');
        $admin->setPassword($password);
        $manager->persist($admin);


        // 2️⃣ Création de quelques événements de test
        for ($i = 1; $i <= 5; $i++) {
            $event = new Event();
            $event->setTitle("Événement ISSAT $i");
            $event->setDescription("Description détaillée de l'événement $i");
            $event->setDate(new \DateTime("+$i days"));
            $event->setLocation("Sousse");
            $event->setSeats(50);
            $event->setImage("https://via.placeholder.com/150");
            $manager->persist($event);
        }

        $manager->flush();
    }
}