<?php

declare(strict_types=1);

namespace App\Tests\Application\Validator\Constraints;

use App\Application\Validator\Constraints\EntityExists;
use App\Domain\User\Entity\User;
use App\Tests\AbstractKernelTestCase;
use App\Tests\TestUtils\Traits\UserTrait;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EntityExistsValidatorTest extends AbstractKernelTestCase
{
    use UserTrait;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function setUp()
    {
        parent::setUp();

        $this->validator = self::$container->get('validator');
    }

    /** @test */
    public function shouldValidateEmptyValue()
    {
        $errors = $this->validator->validate(
            null,
            new EntityExists(['class' => User::class])
        );

        self::assertEquals(0, $errors->count());
    }

    /** @test */
    public function shouldValidateValidValue()
    {
        $publication = $this->entityManager->getRepository(User::class)->findOneBy([]);

        $errors = $this->validator->validate(
            $publication->getId(),
            new EntityExists(['class' => User::class])
        );

        self::assertEquals(0, $errors->count());
    }

    /** @test */
    public function shouldReturnValidationErrors()
    {
        $errors = $this->validator->validate(
            0,
            new EntityExists(['class' => User::class])
        );

        self::assertGreaterThan(0, $errors->count());
        self::assertEquals(EntityExists::ENTITY_NOT_FOUND, $errors->get(0)->getCode());
    }

    /** @test */
    public function shouldValidateValueWithParams()
    {
        $verifiedUser = $this->findRandomUser();
        $unverifiedUser = $this->findRandomUser(false);

        $constraint = new EntityExists([
            'class' => User::class,
            'params' => [
                'emailConfirmed' => true,
            ],
        ]);

        $errors = $this->validator->validate($verifiedUser->getId(), $constraint);

        self::assertEquals(0, $errors->count());

        $errors = $this->validator->validate($unverifiedUser->getId(), $constraint);

        self::assertGreaterThan(0, $errors->count());
        self::assertEquals(EntityExists::ENTITY_NOT_FOUND, $errors->get(0)->getCode());
    }

    /** @test */
    public function shouldValidateValueWithCallback()
    {
        $verifiedUser = $this->findRandomUser();
        $unverifiedUser = $this->findRandomUser(false);

        $constraint = new EntityExists([
            'class' => User::class,
            'callback' => function (QueryBuilder $qb) {
                $qb->andWhere('entity.emailConfirmed = true');
            },
        ]);

        $errors = $this->validator->validate($verifiedUser->getId(), $constraint);

        self::assertEquals(0, $errors->count());

        $errors = $this->validator->validate($unverifiedUser->getId(), $constraint);

        self::assertGreaterThan(0, $errors->count());
        self::assertEquals(EntityExists::ENTITY_NOT_FOUND, $errors->get(0)->getCode());
    }
}
