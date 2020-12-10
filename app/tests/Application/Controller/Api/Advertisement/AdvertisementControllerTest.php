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
use App\Tests\TestUtils\Traits\FileTrait;
use App\Tests\TestUtils\Traits\UserTrait;

class AdvertisementControllerTest extends AbstractRestTestCase
{
    use UserTrait;
    use CategoryTrait;
    use AdvertisementTrait;
    use FileTrait;

    /** @test */
    public function shouldReturnPublishedAdvertisements(): void
    {
        $user = $this->findRandomUser();
        $category = $this->findRandomCategory();

        $response = $this->sendGet(
            sprintf('/api/advertisement?%s', http_build_query(['category' => $category->getId()])),
            [],
            $this->logIn($user->getEmail())
        );

        $responseData = json_decode($response->getContent(), true);

        self::assertEquals(200, $response->getStatusCode());
        self::assertArrayHasKey('limit', $responseData);
        self::assertArrayHasKey('data', $responseData);
        self::assertNotEmpty($responseData['data']);

        foreach ($responseData['data'] as $item) {
            self::assertArrayHasKey('id', $item);
            self::assertArrayHasKey('title', $item);
            self::assertArrayHasKey('state', $item);
            self::assertArrayHasKey('category', $item);
            self::assertArrayHasKey('price', $item);

            self::assertEquals('published', $item['state']);
        }
    }

    /** @test */
    public function shouldReturnValidationErrorsOnPublishedAdvertisementsList(): void
    {
        $user = $this->findRandomUser();

        $response = $this->sendGet(
            sprintf('/api/advertisement?%s', http_build_query(['category' => 'test'])),
            [],
            $this->logIn($user->getEmail())
        );

        $responseData = json_decode($response->getContent(), true);

        self::assertEquals(400, $response->getStatusCode());
        self::assertArrayHasKey('errors', $responseData);
        self::assertArrayHasKey('category', $responseData['errors']);
        self::assertContains('ENTITY_NOT_FOUND', $responseData['errors']['category']);

        $response = $this->sendGet(
            sprintf('/api/advertisement?%s', http_build_query(['category' => ''])),
            [],
            $this->logIn($user->getEmail())
        );

        $responseData = json_decode($response->getContent(), true);

        self::assertEquals(400, $response->getStatusCode());
        self::assertArrayHasKey('errors', $responseData);
        self::assertArrayHasKey('category', $responseData['errors']);
        self::assertContains('IS_BLANK_ERROR', $responseData['errors']['category']);
    }

    /** @test */
    public function shouldFilterPublishedAdvertisementsByPrice(): void
    {
        $user = $this->findRandomUser();
        $category = $this->findRandomCategory();

        $response = $this->sendGet(
            sprintf('/api/advertisement?%s', http_build_query([
                'category' => $category->getId(),
                'price' => ['min' => 5000, 'max' => 7000],
            ])),
            [],
            $this->logIn($user->getEmail())
        );

        $responseData = json_decode($response->getContent(), true);

        self::assertEquals(200, $response->getStatusCode());
        self::assertNotEmpty($responseData['data']);

        foreach ($responseData['data'] as $item) {
            self::assertArrayHasKey('price', $item);

            self::assertThat($item['price']['price'], self::logicalAnd(
                self::greaterThanOrEqual(5000),
                self::lessThanOrEqual(7000),
            ));
        }
    }

    /**
     * @test
     *
     * @dataProvider advertisementStateDataProvider
     *
     * @param string $limit
     * @param string|null $state
     */
    public function shouldReturnMyAdvertisements(string $limit, ?string $state): void
    {
        $user = $this->findRandomUser();

        $response = $this->sendGet(
            sprintf('/api/advertisement/my?%s', http_build_query([
                'state' => $state,
                'limit' => $limit,
            ])),
            [],
            $this->logIn($user->getEmail())
        );

        $responseData = json_decode($response->getContent(), true);

        self::assertEquals(200, $response->getStatusCode());
        self::assertArrayHasKey('limit', $responseData);
        self::assertEquals($responseData['limit'], $limit);
        self::assertArrayHasKey('data', $responseData);
        self::assertNotEmpty($responseData['data']);

        if ($state) {
            foreach ($responseData['data'] as $item) {
                self::assertArrayHasKey('id', $item);
                self::assertArrayHasKey('title', $item);
                self::assertArrayHasKey('state', $item);
                self::assertArrayHasKey('category', $item);
                self::assertArrayHasKey('price', $item);

                self::assertEquals($state, $item['state']);
            }
        }

        $category = $this->findRandomCategory();

        $response = $this->sendGet(
            sprintf('/api/advertisement/my?%s', http_build_query([
                'state' => $state,
                'limit' => $limit,
                'category' => $category->getId(),
            ])),
            [],
            $this->logIn($user->getEmail())
        );

        $responseData = json_decode($response->getContent(), true);

        self::assertEquals(200, $response->getStatusCode());
        self::assertArrayHasKey('limit', $responseData);
        self::assertEquals($responseData['limit'], $limit);
        self::assertArrayHasKey('data', $responseData);
        self::assertNotEmpty($responseData['data']);

        if ($state) {
            foreach ($responseData['data'] as $item) {
                self::assertArrayHasKey('id', $item);
                self::assertArrayHasKey('title', $item);
                self::assertArrayHasKey('state', $item);
                self::assertArrayHasKey('category', $item);
                self::assertArrayHasKey('price', $item);

                self::assertEquals($state, $item['state']);
                self::assertEquals($category->getId(), $item['category']['id']);
            }
        }
    }

    /** @test */
    public function shouldReturnValidationErrorsOnMyAdvertisementsList(): void
    {
        $user = $this->findRandomUser();

        $response = $this->sendGet(
            sprintf('/api/advertisement/my?%s', http_build_query([
                'category' => 'test',
            ])),
            [],
            $this->logIn($user->getEmail())
        );

        $responseData = json_decode($response->getContent(), true);

        self::assertEquals(400, $response->getStatusCode());
        self::assertArrayHasKey('errors', $responseData);
        self::assertArrayHasKey('category', $responseData['errors']);
        self::assertContains('ENTITY_NOT_FOUND', $responseData['errors']['category']);

        $response = $this->sendGet(
            sprintf('/api/advertisement/my?%s', http_build_query([
                'state' => 'test',
            ])),
            [],
            $this->logIn($user->getEmail())
        );

        $responseData = json_decode($response->getContent(), true);

        self::assertEquals(400, $response->getStatusCode());
        self::assertArrayHasKey('errors', $responseData);
        self::assertArrayHasKey('state', $responseData['errors']);
        self::assertContains('NO_SUCH_CHOICE_ERROR', $responseData['errors']['state']);
    }

    /** @test */
    public function shouldReturnReadyForReviewAdvertisements(): void
    {
        $user = $this->findAdmin();
        $category = $this->findRandomCategory();

        $response = $this->sendGet(
            sprintf('/api/advertisement/ready-for-review?%s', http_build_query(['category' => $category->getId()])),
            [],
            $this->logIn($user->getEmail())
        );

        $responseData = json_decode($response->getContent(), true);

        self::assertEquals(200, $response->getStatusCode());
        self::assertArrayHasKey('limit', $responseData);
        self::assertArrayHasKey('data', $responseData);
        self::assertNotEmpty($responseData['data']);

        foreach ($responseData['data'] as $item) {
            self::assertArrayHasKey('id', $item);
            self::assertArrayHasKey('title', $item);
            self::assertArrayHasKey('state', $item);
            self::assertArrayHasKey('category', $item);
            self::assertArrayHasKey('price', $item);

            self::assertEquals('on_review', $item['state']);
        }

        $user = $this->findManager();

        $response = $this->sendGet(
            '/api/advertisement/ready-for-review',
            [],
            $this->logIn($user->getEmail())
        );

        $responseData = json_decode($response->getContent(), true);

        self::assertEquals(200, $response->getStatusCode());
        self::assertArrayHasKey('limit', $responseData);
        self::assertArrayHasKey('data', $responseData);
        self::assertNotEmpty($responseData['data']);

        foreach ($responseData['data'] as $item) {
            self::assertArrayHasKey('id', $item);
            self::assertArrayHasKey('title', $item);
            self::assertArrayHasKey('state', $item);
            self::assertArrayHasKey('category', $item);
            self::assertArrayHasKey('price', $item);

            self::assertEquals('on_review', $item['state']);
        }
    }

    /** @test */
    public function shouldDenyAccessForUserOnViewingReadyForReviewAdvertisements(): void
    {
        $user = $this->findRandomUser();

        $response = $this->sendGet(
            '/api/advertisement/ready-for-review',
            [],
            $this->logIn($user->getEmail())
        );

        self::assertEquals(403, $response->getStatusCode());
    }

    /** @test */
    public function shouldReturnAdvertisementDetailedView(): void
    {
        $advertisement = $this->findAdvertisementByState(new PublishedState());
        $user = $this->getAdvertisementField($advertisement, 'author');

        $response = $this->sendGet(
            sprintf('/api/advertisement/%s', $advertisement->getId()),
            [],
            $this->logIn($user->getEmail())
        );

        $responseData = json_decode($response->getContent(), true);

        self::assertEquals(200, $response->getStatusCode());
        self::assertArrayHasKey('id', $responseData);
        self::assertArrayHasKey('title', $responseData);
        self::assertArrayHasKey('description', $responseData);
        self::assertArrayHasKey('price', $responseData);
        self::assertArrayHasKey('author', $responseData);
        self::assertArrayHasKey('createdAt', $responseData);
        self::assertArrayHasKey('attachments', $responseData);
    }

    /** @test */
    public function shouldCreateAdvertisementWithAttachment(): void
    {
        $user = $this->findRandomUser();
        $category = $this->findRandomCategory();

        $data = [
            'title' => self::$faker->words(3, true),
            'description' => self::$faker->text(1000),
            'price' => 400,
            'currency' => 'USD',
            'category' => $category->getId(),
            'attachments' => [
                [
                    'file' => 'UNIQUEKEY',
                    'featured' => true,
                ],
            ],
        ];

        $response = $this->sendFormDataRequest(
            '/api/advertisement',
            [
                'data' => json_encode($data),
            ],
            [
                'UNIQUEKEY' => $this->createUploadedImageFile(600, 400),
            ],
            [],
            $this->logIn($user->getEmail())
        );

        $responseData = json_decode($response->getContent(), true);

        self::assertEquals(200, $response->getStatusCode());
        self::assertArrayHasKey('id', $responseData);

        /** @var Advertisement $advertisement */
        $advertisement = $this->entityManager->getRepository(Advertisement::class)->find($responseData['id']);
        $description = $this->getAdvertisementField($advertisement, 'description');
        $attachments = $this->getAdvertisementField($advertisement, 'attachments');

        self::assertTrue($advertisement->isDraft());
        self::assertTrue($advertisement->isAuthor($user));
        self::assertEquals($description->getTitle(), $data['title']);
        self::assertEquals($description->getDescription(), $data['description']);
        self::assertCount(1, $attachments);
    }

    /**
     * @test
     *
     * @dataProvider invalidAdvertisementDataProvider
     *
     * @param array $data
     * @param array $files
     * @param array $errors
     */
    public function shouldReturnValidationErrors(array $data, array $files, array $errors): void
    {
        $user = $this->findRandomUser();

        $response = $this->sendFormDataRequest(
            '/api/advertisement',
            [
                'data' => json_encode($data),
            ],
            $files,
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
    public function shouldSendAdvertisementToReview()
    {
        $advertisement = $this->findAdvertisementByState(new DraftState());
        $user = $this->getAdvertisementAuthor($advertisement);

        self::assertTrue($advertisement->isDraft());

        $response = $this->sendPost(
            sprintf('/api/advertisement/%s/send-to-review', $advertisement->getId()),
            [],
            [],
            $this->logIn($user->getEmail())
        );

        self::assertEquals(204, $response->getStatusCode());
        self::assertTrue($advertisement->isOnReview());
    }

    /** @test */
    public function shouldDenyAccessOnSendingAdvertisementToReview(): void
    {
        $advertisement = $this->findAdvertisementByState(new DraftState());
        $user = $this->findUserExcept(
            $this->getAdvertisementAuthor($advertisement)
        );

        self::assertTrue($advertisement->isDraft());

        $response = $this->sendPost(
            sprintf('/api/advertisement/%s/send-to-review', $advertisement->getId()),
            [],
            [],
            $this->logIn($user->getEmail())
        );

        self::assertEquals(403, $response->getStatusCode());
        self::assertTrue($advertisement->isDraft());
    }

    /** @test */
    public function shouldPublishAdvertisement(): void
    {
        $advertisement = $this->findAdvertisementByState(new OnReviewState());
        $user = $this->findAdmin();

        self::assertTrue($advertisement->isOnReview());

        $response = $this->sendPost(
            sprintf('/api/advertisement/%s/publish', $advertisement->getId()),
            [],
            [],
            $this->logIn($user->getEmail())
        );

        self::assertEquals(204, $response->getStatusCode());
        self::assertTrue($advertisement->isPublished());
    }

    /** @test */
    public function shouldDenyAccessOnAdvertisementPublishing(): void
    {
        $advertisement = $this->findAdvertisementByState(new OnReviewState());
        $user = $this->getAdvertisementAuthor($advertisement);

        self::assertTrue($advertisement->isOnReview());

        $response = $this->sendPost(
            sprintf('/api/advertisement/%s/publish', $advertisement->getId()),
            [],
            [],
            $this->logIn($user->getEmail())
        );

        self::assertEquals(403, $response->getStatusCode());
        self::assertTrue($advertisement->isOnReview());
    }

    /** @test */
    public function shouldSendBackAdvertisement(): void
    {
        $advertisement = $this->findAdvertisementByState(new OnReviewState());
        $user = $this->findAdmin();

        self::assertTrue($advertisement->isOnReview());

        $response = $this->sendPost(
            sprintf('/api/advertisement/%s/send-back', $advertisement->getId()),
            [
                'reason' => self::$faker->text,
            ],
            [],
            $this->logIn($user->getEmail())
        );

        self::assertEquals(204, $response->getStatusCode());
        self::assertTrue($advertisement->isDraft());
    }

    /** @test */
    public function shouldDenyAccessOnAdvertisementSendingBack(): void
    {
        $advertisement = $this->findAdvertisementByState(new OnReviewState());
        $user = $this->getAdvertisementField($advertisement, 'author');

        self::assertTrue($advertisement->isOnReview());

        $response = $this->sendPost(
            sprintf('/api/advertisement/%s/send-back', $advertisement->getId()),
            [
                'reason' => self::$faker->text,
            ],
            [],
            $this->logIn($user->getEmail())
        );

        self::assertEquals(403, $response->getStatusCode());
        self::assertTrue($advertisement->isOnReview());
    }

    /** @test */
    public function shouldReturnValidationErrorsOnAdvertisementSendingBack(): void
    {
        $advertisement = $this->findAdvertisementByState(new OnReviewState());
        $user = $this->findAdmin();

        self::assertTrue($advertisement->isOnReview());

        $response = $this->sendPost(
            sprintf('/api/advertisement/%s/send-back', $advertisement->getId()),
            [
                'reason' => null,
            ],
            [],
            $this->logIn($user->getEmail())
        );

        $responseData = json_decode($response->getContent(), true);

        self::assertEquals(400, $response->getStatusCode());
        self::assertTrue($advertisement->isOnReview());
        self::assertArrayHasKey('errors', $responseData);
        self::assertArrayHasKey('reason', $responseData['errors']);
        self::assertContains('IS_BLANK_ERROR', $responseData['errors']['reason']);
    }

    /** @test */
    public function shouldArchiveAdvertisement(): void
    {
        $advertisement = $this->findAdvertisementByState(new PublishedState());
        $user = $this->getAdvertisementAuthor($advertisement);

        self::assertTrue($advertisement->isPublished());

        $response = $this->sendPost(
            sprintf('/api/advertisement/%s/archive', $advertisement->getId()),
            [],
            [],
            $this->logIn($user->getEmail())
        );

        self::assertEquals(204, $response->getStatusCode());
        self::assertTrue($advertisement->isArchived());
    }

    public function invalidAdvertisementDataProvider(): array
    {
        return [
            [
                'data' => [
                    'title' => null,
                    'description' => null,
                    'price' => null,
                    'currency' => null,
                    'category' => null,
                    'attachments' => [],
                ],
                'files' => [],
                'errors' => [
                    'title' => 'IS_BLANK_ERROR',
                    'description' => 'IS_BLANK_ERROR',
                    'price' => 'IS_BLANK_ERROR',
                    'currency' => 'IS_BLANK_ERROR',
                    'category' => 'IS_BLANK_ERROR',
                    'attachments' => 'IS_BLANK_ERROR',
                ],
            ],
            [
                'data' => [
                    'title' => [12],
                    'description' => 55,
                    'price' => "null",
                    'currency' => 'test',
                    'category' => 'test',
                    'attachments' => [
                        [
                            'file' => 'key',
                            'featured' => 25,
                        ],
                    ],
                ],
                'files' => [
                    'key' => $this->createUploadedTxtFile(),
                ],
                'errors' => [
                    'title' => 'INVALID_TYPE_ERROR',
                    'description' => 'INVALID_TYPE_ERROR',
                    'price' => 'INVALID_TYPE_ERROR',
                    'currency' => 'ENTITY_NOT_FOUND',
                    'category' => 'ENTITY_NOT_FOUND',
                    'attachments[0].file' => 'INVALID_MIME_TYPE_ERROR',
                    'attachments[0].featured' => 'INVALID_TYPE_ERROR',
                ],
            ],
            [
                'data' => [
                    'title' => [12],
                    'description' => 55,
                    'price' => "null",
                    'currency' => 'test',
                    'category' => 'test',
                    'attachments' => [],
                ],
                'files' => [],
                'errors' => [
                    'title' => 'INVALID_TYPE_ERROR',
                    'description' => 'INVALID_TYPE_ERROR',
                    'price' => 'INVALID_TYPE_ERROR',
                    'currency' => 'ENTITY_NOT_FOUND',
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
                    'attachments' => [],
                ],
                'files' => [],
                'errors' => [
                    'title' => 'TOO_LONG_ERROR',
                    'price' => 'TOO_LOW_ERROR',
                    'category' => 'INVALID_TYPE_ERROR',
                ],
            ],
        ];
    }

    public function advertisementStateDataProvider(): array
    {
        return [
            [
                'limit' => 10,
                'state' => 'draft',
            ],
            [
                'limit' => 5,
                'state' => 'on_review',
            ],
            [
                'limit' => 7,
                'state' => 'published',
            ],
            [
                'limit' => 20,
                'state' => 'archived',
            ],
            [
                'limit' => 30,
                'state' => null,
            ],
        ];
    }
}
