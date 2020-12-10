<?php

declare(strict_types=1);

namespace App\Tests\Application\Controller\Api\User;

use App\Domain\User\Entity\User;
use App\Tests\Application\Controller\Api\AbstractRestTestCase;
use App\Tests\TestUtils\Traits\UserTrait;

class UserControllerTest extends AbstractRestTestCase
{
    use UserTrait;

    public function setUp()
    {
        parent::setUp();

        $this->client->disableReboot();
    }

    /** @test */
    public function shouldUpdatePersonalData()
    {
        $user = $this->findRandomUser();

        $data = [
            'email' => $user->getEmail(),
            'firstName' => self::$faker->firstName,
            'lastName' => self::$faker->lastName,
            'biography' => self::$faker->text(),
        ];

        $response = $this->sendPut(
            '/api/user/personal-data',
            $data,
            [],
            $this->logIn($user->getEmail())
        );

        $personalData = $this->getUserPersonalData($user);
        $newEmail = $this->getUserField($user, 'newEmail');
        $token = $this->getUserField($user, 'confirmationToken');



        self::assertEquals(204, $response->getStatusCode());

        self::assertEquals($data['firstName'], $personalData->getFirstName());
        self::assertEquals($data['lastName'], $personalData->getLastName());
        self::assertEquals($data['biography'], $personalData->getBiography());
        self::assertEmpty($newEmail);
        self::assertEmpty($token);
    }

    /** @test */
    public function shouldUpdatePersonalDataWithEmail()
    {
        $user = $this->findRandomUser();

        $data = [
            'email' => self::$faker->unique(true)->safeEmail,
            'firstName' => self::$faker->firstName,
            'lastName' => self::$faker->lastName,
            'biography' => self::$faker->text(),
        ];

        $response = $this->sendPut(
            '/api/user/personal-data',
            $data,
            [],
            $this->logIn($user->getEmail())
        );

        $personalData = $this->getUserPersonalData($user);
        $newEmail = $this->getUserField($user, 'newEmail');
        $token = $this->getUserField($user, 'confirmationToken');

        self::assertEquals(204, $response->getStatusCode());

        self::assertEquals($data['firstName'], $personalData->getFirstName());
        self::assertEquals($data['lastName'], $personalData->getLastName());
        self::assertEquals($data['biography'], $personalData->getBiography());
        self::assertNotEquals($data['email'], $personalData->getEmail());
        self::assertEquals($data['email'], $newEmail);
        self::assertNotEmpty($token);
    }

    /**
     * @test
     *
     * @dataProvider invalidPersonalDataProvider
     *
     * @param array $data
     * @param array $errors
     */
    public function shouldReturnValidationErrors(array $data, array $errors)
    {
        $user = $this->findRandomUser();

        $response = $this->sendPut(
            '/api/user/personal-data',
            $data,
            [],
            $this->logIn($user->getEmail())
        );

        $responseData = json_decode($response->getContent(), true);

        self::assertEquals(422, $response->getStatusCode());
        self::assertArrayHasKey('errors', $responseData);

        foreach ($errors as $field => $error) {
            self::assertArrayHasKey($field, $responseData['errors']);
            self::assertContains($error, $responseData['errors'][$field]);
        }
    }

    /** @test */
    public function shouldVerifyNewUserEmail()
    {
        $user = $this->findRandomUser();

        $data = [
            'email' => self::$faker->unique(true)->safeEmail,
            'firstName' => self::$faker->firstName,
            'lastName' => self::$faker->lastName,
            'biography' => self::$faker->text(),
        ];

        $response = $this->sendPut(
            '/api/user/personal-data',
            $data,
            [],
            $this->logIn($user->getEmail())
        );

        $personalData = $this->getUserPersonalData($user);
        $newEmail = $this->getUserField($user, 'newEmail');
        $token = $this->getUserField($user, 'confirmationToken');

        self::assertEquals(204, $response->getStatusCode());

        self::assertEquals($data['firstName'], $personalData->getFirstName());
        self::assertEquals($data['lastName'], $personalData->getLastName());
        self::assertEquals($data['biography'], $personalData->getBiography());
        self::assertNotEquals($data['email'], $personalData->getEmail());
        self::assertEquals($data['email'], $newEmail);
        self::assertNotEmpty($token);

        $response = $this->sendPost(
            '/api/user/email/verify',
            ['token' => $token],
            [],
            $this->logIn($user->getEmail())
        );

        $responseData = json_decode($response->getContent(), true);

        self::assertEquals(200, $response->getStatusCode());
        self::assertArrayHasKey('token', $responseData);
        self::assertArrayHasKey('refreshToken', $responseData);

        $this->entityManager->clear();

        $user = $this->entityManager->find(User::class, $user->getId());
        $personalData = $this->getUserPersonalData($user);
        $newEmail = $this->getUserField($user, 'newEmail');
        $token = $this->getUserField($user, 'confirmationToken');

        self::assertEquals($data['email'], $personalData->getEmail());
        self::assertEmpty($newEmail);
        self::assertEmpty($token);
    }

    public function invalidPersonalDataProvider()
    {
        return [
            [
                'data' => [
                    'email' => null,
                    'firstName' => null,
                    'lastName' => null,
                ],
                'errors' => [
                    'email' => 'IS_BLANK_ERROR',
                    'firstName' => 'IS_BLANK_ERROR',
                    'lastName' => 'IS_BLANK_ERROR',
                ],
            ],
            [
                'data' => [
                    'email' => self::$faker->text(600),
                    'firstName' => self::$faker->text(600),
                    'lastName' => self::$faker->text(600),
                ],
                'errors' => [
                    'email' => 'TOO_LONG_ERROR',
                    'firstName' => 'TOO_LONG_ERROR',
                    'lastName' => 'TOO_LONG_ERROR',
                ],
            ],
            [
                'data' => [
                    'email' => 'test',
                    'firstName' => [12],
                    'lastName' => 55,
                ],
                'errors' => [
                    'email' => 'STRICT_CHECK_FAILED_ERROR',
                    'firstName' => 'INVALID_TYPE_ERROR',
                    'lastName' => 'INVALID_TYPE_ERROR',
                ],
            ],
        ];
    }
}
