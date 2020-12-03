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

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('limit', $responseData);
        $this->assertArrayHasKey('data', $responseData);
        $this->assertNotEmpty($responseData['data']);

        foreach ($responseData['data'] as $item) {
            $this->assertArrayHasKey('id', $item);
            $this->assertArrayHasKey('name', $item);
        }
    }
}
