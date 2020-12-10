<?php

declare(strict_types=1);

namespace App\Tests\Application\Controller\Api\User;

use App\Domain\User\Entity\User;
use App\Tests\Application\Controller\Api\AbstractRestTestCase;
use App\Tests\TestUtils\Traits\UserTrait;

class SignUpControllerTest extends AbstractRestTestCase
{
    use UserTrait;

    /** @test */
    public function shouldSignUp()
    {
        $data = [
            'email' => self::$faker->unique()->safeEmail,
            'password' => self::$faker->password(),
            'firstName' => self::$faker->firstName,
            'lastName' => self::$faker->lastName,
            'biography' => self::$faker->text(),
        ];

        $response = $this->sendPost('/api/sign-up', $data);

        $responseData = json_decode($response->getContent(), true);

        self::assertEquals(200, $response->getStatusCode());
        self::assertArrayHasKey('id', $responseData);

        /** @var User $user */
        $user = $this->entityManager->getRepository(User::class)->find($responseData['id']);
        $personalData = $this->getUserPersonalData($user);

        self::assertInstanceOf(User::class, $user);
        self::assertEquals($user->getEmail(), $data['email']);
        self::assertEquals($personalData->getFirstName(), $data['firstName']);
        self::assertEquals($personalData->getLastName(), $data['lastName']);
        self::assertEquals($personalData->getBiography(), $data['biography']);
        self::assertFalse($user->isManagerOrAdmin());
    }

    /**
     * @test
     *
     * @dataProvider invalidUserDataProvider
     *
     * @param array $data
     * @param array $errors
     */
    public function shouldReturnValidationErrors(array $data, array $errors)
    {
        $response = $this->sendPost('/api/sign-up', $data);

        $responseData = json_decode($response->getContent(), true);

        self::assertEquals(422, $response->getStatusCode());
        self::assertArrayHasKey('errors', $responseData);

        foreach ($errors as $field => $error) {
            self::assertArrayHasKey($field, $responseData['errors']);
            self::assertContains($error, $responseData['errors'][$field]);
        }
    }

    /** @test */
    public function shouldReturnEmailExistsValidationError()
    {
        $user = $this->findRandomUser();

        $data = [
            'email' => $user->getEmail(),
            'password' => self::$faker->password(),
            'firstName' => self::$faker->firstName,
            'lastName' => self::$faker->lastName,
        ];

        $response = $this->sendPost('/api/sign-up', $data);

        $responseData = json_decode($response->getContent(), true);

        self::assertEquals(422, $response->getStatusCode());
        self::assertArrayHasKey('errors', $responseData);
        self::assertArrayHasKey('email', $responseData['errors']);
        self::assertEquals(['NOT_UNIQUE_ERROR'], $responseData['errors']['email']);
    }

    /** @test */
    public function shouldVerifyUser()
    {
        $user = $this->findRandomUser(false);

        self::assertFalse($user->isEmailConfirmed());

        $response = $this->sendPost(sprintf('/api/sign-up/verify/email/%s', $user->getConfirmationToken()), []);

        $responseData = json_decode($response->getContent(), true);

        self::assertEquals(200, $response->getStatusCode());
        self::assertArrayHasKey('token', $responseData);
        self::assertArrayHasKey('refreshToken', $responseData);

        self::assertTrue($user->isEmailConfirmed());
    }

    public function invalidUserDataProvider()
    {
        return [
            [
                'data' => [
                    'email' => null,
                    'password' => null,
                    'firstName' => null,
                    'lastName' => null,
                ],
                'errors' => [
                    'email' => 'IS_BLANK_ERROR',
                    'password' => 'IS_BLANK_ERROR',
                    'firstName' => 'IS_BLANK_ERROR',
                    'lastName' => 'IS_BLANK_ERROR',
                ],
            ],
            [
                'data' => [
                    'email' => self::$faker->text(600),
                    'password' => self::$faker->text(600),
                    'firstName' => self::$faker->text(600),
                    'lastName' => self::$faker->text(600),
                ],
                'errors' => [
                    'email' => 'TOO_LONG_ERROR',
                    'password' => 'TOO_LONG_ERROR',
                    'firstName' => 'TOO_LONG_ERROR',
                    'lastName' => 'TOO_LONG_ERROR',
                ],
            ],
            [
                'data' => [
                    'email' => 'test',
                    'password' => 'aa',
                    'firstName' => [12],
                    'lastName' => 55,
                ],
                'errors' => [
                    'email' => 'STRICT_CHECK_FAILED_ERROR',
                    'password' => 'TOO_SHORT_ERROR',
                    'firstName' => 'INVALID_TYPE_ERROR',
                    'lastName' => 'INVALID_TYPE_ERROR',
                ],
            ],
        ];
    }
}
