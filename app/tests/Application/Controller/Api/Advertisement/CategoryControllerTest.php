<?php

declare(strict_types=1);

namespace App\Tests\Application\Controller\Api\Advertisement;

use App\Tests\Application\Controller\Api\AbstractRestTestCase;
use App\Tests\TestUtils\Traits\UserTrait;

class CategoryControllerTest extends AbstractRestTestCase
{
    use UserTrait;

    /** @test */
    public function shouldReturnCategories()
    {
        $user = $this->findRandomUser();

        $response = $this->sendGet(
            '/api/category',
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
            self::assertArrayHasKey('name', $item);
        }
    }
}
