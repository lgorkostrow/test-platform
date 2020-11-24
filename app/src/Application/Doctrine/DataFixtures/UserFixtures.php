<?php

declare(strict_types=1);

namespace App\Application\Doctrine\DataFixtures;

use App\Domain\User\Entity\User;
use App\Domain\User\ValueObject\PersonalData;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class UserFixtures extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        $this->createUsers($manager, 10);
        $this->createAdmins($manager, 5);
        $this->createUsers($manager, 5, false);

        $manager->flush();
    }

    private function createUsers(ObjectManager $manager, int $count = 10, bool $verified = true)
    {
        for ($i = 0; $i < $count; $i++) {
            $user = User::createUser(
                Uuid::uuid4()->toString(),
                new PersonalData($this->faker->firstName, $this->faker->lastName, $this->faker->safeEmail),
                md5(uniqid()),
            );

            if ($verified) {
                $user->verify();
            }

            $manager->persist($user);
        }
    }

    private function createAdmins(ObjectManager $manager, int $count = 10)
    {
        for ($i = 0; $i < $count; $i++) {
            $user = User::createAdmin(
                Uuid::uuid4()->toString(),
                new PersonalData($this->faker->firstName, $this->faker->lastName, $this->faker->safeEmail),
                md5(uniqid()),
            );

            $user->verify();

            $manager->persist($user);
        }
    }
}
