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
    public function shouldCreateAdvertisementWithAttachment()
    {
        $user = $this->findRandomUser();
        $category = $this->findRandomCategory();

        $data = [
            'title' => self::$faker->words(3, true),
            'description' => self::$faker->text(1000),
            'price' => 400,
            'currency' => 'USD',
            'category' => $category->getId(),
            'attachments' => ['UNIQUEKEY'],
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

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('id', $responseData);

        /** @var Advertisement $advertisement */
        $advertisement = $this->entityManager->getRepository(Advertisement::class)->find($responseData['id']);
        $description = $this->getAdvertisementField($advertisement, 'description');
        $attachments = $this->getAdvertisementField($advertisement, 'attachments');

        $this->assertTrue($advertisement->isDraft());
        $this->assertTrue($advertisement->isAuthor($user));
        $this->assertEquals($description->getTitle(), $data['title']);
        $this->assertEquals($description->getDescription(), $data['description']);
        $this->assertCount(1, $attachments);
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
    public function shouldReturnValidationErrors(array $data, array $files, array $errors)
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
    public function shouldSendBackAdvertisement()
    {
        $advertisement = $this->findAdvertisementByState(new OnReviewState());
        $user = $this->findAdmin();

        $this->assertTrue($advertisement->isOnReview());

        $response = $this->sendPost(
            sprintf('/api/advertisement/%s/send-back', $advertisement->getId()),
            [
                'reason' => self::$faker->text,
            ],
            [],
            $this->logIn($user->getEmail())
        );

        $this->assertEquals(204, $response->getStatusCode());
        $this->assertTrue($advertisement->isDraft());
    }

    /** @test */
    public function shouldDenyAccessOnAdvertisementSendingBack()
    {
        $advertisement = $this->findAdvertisementByState(new OnReviewState());
        $user = $this->getAdvertisementField($advertisement, 'author');

        $this->assertTrue($advertisement->isOnReview());

        $response = $this->sendPost(
            sprintf('/api/advertisement/%s/send-back', $advertisement->getId()),
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
                    'attachments' => ['key'],
                ],
                'files' => [
                    'key' => $this->createUploadedTxtFile(),
                ],
                'errors' => [
                    'title' => 'INVALID_TYPE_ERROR',
                    'description' => 'INVALID_TYPE_ERROR',
                    'price' => 'INVALID_TYPE_ERROR',
                    'currency' => 'NO_SUCH_CHOICE_ERROR',
                    'category' => 'ENTITY_NOT_FOUND',
                    'attachments[0]' => 'INVALID_MIME_TYPE_ERROR',
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
}
