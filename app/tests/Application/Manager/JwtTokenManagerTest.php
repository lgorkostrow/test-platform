<?php

declare(strict_types=1);

namespace App\Tests\Application\Manager;

use App\Application\Manager\JwtTokenManager;
use App\Tests\AbstractKernelTestCase;
use App\Tests\TestUtils\Traits\UserTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class JwtTokenManagerTest extends AbstractKernelTestCase
{
    use UserTrait;

    /**
     * @var JwtTokenManager|null
     */
    private ?JwtTokenManager $jwtManager;

    /**
     * @var RequestStack|null
     */
    private ?RequestStack $requestStack;

    protected function setUp()
    {
        parent::setUp();

        $this->jwtManager = self::$container->get(JwtTokenManager::class);

        $this->requestStack = self::$container->get(RequestStack::class);
    }

    /**
     * @test
     */
    public function shouldCreateJwtToken()
    {
        $this->requestStack->push(new Request());

        $user = $this->findRandomUser();

        $token = $this->jwtManager->create($user);

        self::assertIsArray($token);
        self::assertArrayHasKey('token', $token);
        self::assertArrayHasKey('refreshToken', $token);
    }

    /** @test */
    public function shouldEncodeData()
    {
        $user = $this->findRandomUser();

        $token = $this->jwtManager->encode($user);

        self::assertIsString($token);
    }

    /** @test */
    public function shouldDecodeData()
    {
        $user = $this->findRandomUser();

        $token = $this->jwtManager->encode($user);
        $decoded = $this->jwtManager->decode($token);

        self::assertIsArray($decoded);
        self::assertArrayHasKey('username', $decoded);
        self::assertArrayHasKey('exp', $decoded);
        self::assertEquals($decoded['username'], $user->getEmail());
    }
}
