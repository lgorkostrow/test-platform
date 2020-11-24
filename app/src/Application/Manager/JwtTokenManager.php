<?php

namespace App\Application\Manager;

use Gesdinet\JWTRefreshTokenBundle\EventListener\AttachRefreshTokenOnSuccessListener;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManager as RefreshTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class JwtTokenManager
{
    /**
     * @var JWTTokenManagerInterface
     */
    private JWTTokenManagerInterface $jwtManager;

    /**
     * @var AttachRefreshTokenOnSuccessListener
     */
    private AttachRefreshTokenOnSuccessListener $refreshTokenOnSuccessListener;

    /**
     * @var RefreshTokenManagerInterface
     */
    private RefreshTokenManagerInterface $refreshTokenManager;

    /**
     * @var JWTEncoderInterface
     */
    private JWTEncoderInterface $encoder;

    public function __construct(
        JWTTokenManagerInterface $jwtManager,
        AttachRefreshTokenOnSuccessListener $refreshTokenOnSuccessListener,
        RefreshTokenManagerInterface $refreshTokenManager,
        JWTEncoderInterface $encoder
    ) {
        $this->jwtManager = $jwtManager;
        $this->refreshTokenOnSuccessListener = $refreshTokenOnSuccessListener;
        $this->refreshTokenManager = $refreshTokenManager;
        $this->encoder = $encoder;
    }

    public function create(UserInterface $user): array
    {
        $jwtSuccessEvent = new AuthenticationSuccessEvent([], $user, new Response());

        $this->refreshTokenOnSuccessListener->attachRefreshToken($jwtSuccessEvent);

        $refreshToken = $this->refreshTokenManager->getLastFromUsername($user->getUsername());

        return [
            'token' => $this->jwtManager->create($user),
            'refreshToken' => $refreshToken->getRefreshToken(),
        ];
    }

    public function encode(UserInterface $user, int $ttl = 300): string
    {
        return $this->encoder->encode([
            'username' => $user->getUsername(),
            'exp' => time() + $ttl,
        ]);
    }

    public function decode(string $token): array
    {
        return $this->encoder->decode($token);
    }
}
