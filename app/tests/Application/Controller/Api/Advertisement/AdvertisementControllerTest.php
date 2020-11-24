<?php

declare(strict_types=1);

namespace App\Tests\Application\Controller\Api\Advertisement;

use App\Domain\Advertisement\Entity\Advertisement;
use App\Domain\Advertisement\State\Advertisement\DraftState;
use App\Domain\Advertisement\State\Advertisement\OnReviewState;
use App\Domain\Advertisement\State\Advertisement\PublishedState;
use App\Tests\Application\Controller\Api\AbstractRestTestCase;
use App\Tests\TestUtils\Traits\AdvertisementTrait;
use App\Tests\TestUtils\Traits\CategoryTrait;
use App\Tests\TestUtils\Traits\UserTrait;

class AdvertisementControllerTest extends AbstractRestTestCase
{
    use UserTrait;
    use CategoryTrait;
    use AdvertisementTrait;

    /** @test */
    public function shouldCreateAdvertisement()
    {
        $user = $this->findRandomUser();
        $category = $this->findRandomCategory();

        $data = [
            'title' => self::$faker->words(3, true),
            'description' => self::$faker->text(1000),
            'price' => 400,
            'currency' => 'USD',
            'category' => $category->getId(),
        ];

        $response = $this->sendPost(
            '/api/advertisement',
            $data,
            [],
            $this->logIn($user->getEmail()),
        );

        $responseData = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('id', $responseData);

        /** @var Advertisement $advertisement */
        $advertisement = $this->entityManager->getRepository(Advertisement::class)->find($responseData['id']);
        $description = $this->getAdvertisementDescription($advertisement);

        $this->assertTrue($advertisement->isDraft());
        $this->assertTrue($advertisement->isAuthor($user));
        $this->assertEquals($description->getTitle(), $data['title']);
        $this->assertEquals($description->getDescription(), $data['description']);
    }

    /**
     * @test
     *
     * @dataProvider invalidAdvertisementDataProvider
     *
     * @param array $data
     * @param array $errors
     */
    public function shouldReturnValidationErrors(array $data, array $errors)
    {
        $user = $this->findRandomUser();

        $response = $this->sendPost('/api/advertisement', $data, [], $this->logIn($user->getEmail()));

        $responseData = json_decode($response->getContent(), true);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertArrayHasKey('errors', $responseData);

        foreach ($errors as $field => $error) {
            $this->assertArrayHasKey($field, $responseData['errors']);
            $this->assertContains($error, $responseData['errors'][$field]);
        }
    }

    /** @test */
    public function shouldSendAdvertisementToReview()
    {
        $advertisement = $this->findAdvertisementByState(new DraftState());
        $user = $this->getAdvertisementAuthor($advertisement);

        $this->assertTrue($advertisement->isDraft());

        $response = $this->sendPost(
            sprintf('/api/advertisement/%s/send-to-review', $advertisement->getId()),
            [],
            [],
            $this->logIn($user->getEmail())
        );

        $this->assertEquals(204, $response->getStatusCode());
        $this->assertTrue($advertisement->isOnReview());
    }

    /** @test */
    public function shouldDenyAccessOnSendingAdvertisementToReview()
    {
        $advertisement = $this->findAdvertisementByState(new DraftState());
        $user = $this->findUserExcept(
            $this->getAdvertisementAuthor($advertisement)
        );

        $this->assertTrue($advertisement->isDraft());

        $response = $this->sendPost(
            sprintf('/api/advertisement/%s/send-to-review', $advertisement->getId()),
            [],
            [],
            $this->logIn($user->getEmail())
        );

        $this->assertEquals(403, $response->getStatusCode());
        $this->assertTrue($advertisement->isDraft());
    }

    /** @test */
    public function shouldPublishAdvertisement()
    {
        $advertisement = $this->findAdvertisementByState(new OnReviewState());
        $user = $this->findAdmin();

        $this->assertTrue($advertisement->isOnReview());

        $response = $this->sendPost(
            sprintf('/api/advertisement/%s/publish', $advertisement->getId()),
            [],
            [],
            $this->logIn($user->getEmail())
        );

        $this->assertEquals(204, $response->getStatusCode());
        $this->assertTrue($advertisement->isPublished());
    }

    /** @test */
    public function shouldDenyAccessOnAdvertisementPublishing()
    {
        $advertisement = $this->findAdvertisementByState(new OnReviewState());
        $user = $this->getAdvertisementAuthor($advertisement);

        $this->assertTrue($advertisement->isOnReview());

        $response = $this->sendPost(
            sprintf('/api/advertisement/%s/publish', $advertisement->getId()),
            [],
            [],
            $this->logIn($user->getEmail())
        );

        $this->assertEquals(403, $response->getStatusCode());
        $this->assertTrue($advertisement->isOnReview());
    }

    /** @test */
    public function shouldArchiveAdvertisement()
    {
        $advertisement = $this->findAdvertisementByState(new PublishedState());
        $user = $this->getAdvertisementAuthor($advertisement);

        $this->assertTrue($advertisement->isPublished());

        $response = $this->sendPost(
            sprintf('/api/advertisement/%s/archive', $advertisement->getId()),
            [],
            [],
            $this->logIn($user->getEmail())
        );

        $this->assertEquals(204, $response->getStatusCode());
        $this->assertTrue($advertisement->isArchived());
    }

    public function invalidAdvertisementDataProvider()
    {
        return [
            [
                'data' => [
                    'title' => null,
                    'description' => null,
                    'price' => null,
                    'currency' => null,
                    'category' => null,
                ],
                'errors' => [
                    'title' => 'IS_BLANK_ERROR',
                    'description' => 'IS_BLANK_ERROR',
                    'price' => 'IS_BLANK_ERROR',
                    'currency' => 'IS_BLANK_ERROR',
                    'category' => 'IS_BLANK_ERROR',
                ],
            ],
            [
                'data' => [
                    'title' => [12],
                    'description' => 55,
                    'price' => "null",
                    'currency' => 'test',
                    'category' => 'test',
                ],
                'errors' => [
                    'title' => 'INVALID_TYPE_ERROR',
                    'description' => 'INVALID_TYPE_ERROR',
                    'price' => 'INVALID_TYPE_ERROR',
                    'currency' => 'NO_SUCH_CHOICE_ERROR',
                    'category' => 'ENTITY_NOT_FOUND',
                ],
            ],
            [
                'data' => [
                    'title' => [12],
                    'description' => 55,
                    'price' => "null",
                    'currency' => 'test',
                    'category' => 'test',
                ],
                'errors' => [
                    'title' => 'INVALID_TYPE_ERROR',
                    'description' => 'INVALID_TYPE_ERROR',
                    'price' => 'INVALID_TYPE_ERROR',
                    'currency' => 'NO_SUCH_CHOICE_ERROR',
                    'category' => 'ENTITY_NOT_FOUND',
                ],
            ],
            [
                'data' => [
                    'title' => self::$faker->text(600),
                    'description' => self::$faker->text(600),
                    'price' => -10,
                    'currency' => 'USD',
                    'category' => 50,
                ],
                'errors' => [
                    'title' => 'TOO_LONG_ERROR',
                    'price' => 'TOO_LOW_ERROR',
                    'category' => 'INVALID_TYPE_ERROR',
                ],
            ],
        ];
    }
}
