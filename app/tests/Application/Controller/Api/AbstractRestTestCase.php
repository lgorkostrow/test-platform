<?php

declare(strict_types=1);

namespace App\Tests\Application\Controller\Api;

use App\Domain\User\Entity\User;
use App\Tests\AbstractWebTestCase;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

class AbstractRestTestCase extends AbstractWebTestCase
{
    /**  @var JWTTokenManagerInterface|null */
    protected ?JWTTokenManagerInterface $jwtManager;

    /** @var RequestStack|null */
    private ?RequestStack $requestStack;

    public function setUp()
    {
        parent::setUp();

        $this->jwtManager = self::$container->get(JWTTokenManagerInterface::class);
        $this->requestStack = self::$container->get(RequestStack::class);
    }

    protected function logIn(string $username = null): ?string
    {
        if ($username) {
            $user = $this->entityManager->getRepository(User::class)->findOneBy([
                'personalData.email' => $username,
                'emailConfirmed' => true,
            ]);
        } else {
            $user = $this->entityManager->getRepository(User::class)->findOneBy([]);
        }

        if (!$user instanceof User) {
            return null;
        }

        $this->requestStack->push(new Request());

        return $this->jwtManager->create($user);
    }

    protected function sendPost(string $resource, array $data, array $headers = [], string $apiToken = null)
    {
        return $this->sendJSONRequest('POST', $resource, $data, $headers, $apiToken);
    }

    protected function sendPut(string $resource, array $data, array $headers = [], string $apiToken = null)
    {
        return $this->sendJSONRequest('PUT', $resource, $data, $headers, $apiToken);
    }

    protected function sendPatch(string $resource, array $data, array $headers = [], string $apiToken = null)
    {
        return $this->sendJSONRequest('PATCH', $resource, $data, $headers, $apiToken);
    }

    protected function sendGet(string $resource, array $headers = [], string $apiToken = null, array $params = [])
    {
        return $this->sendJSONRequest('GET', $resource, $params, $headers, $apiToken);
    }

    protected function sendDelete(string $resource, array $headers = [], string $apiToken = null)
    {
        return $this->sendJSONRequest('DELETE', $resource, [], $headers, $apiToken);
    }

    protected function sendJSONRequest(
        string $method,
        string $resource,
        array $data = [],
        array $headers = [],
        string $apiToken = null
    ): Response {
        $headers = array_merge([
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => $apiToken ? 'Bearer '.$apiToken : '',
        ], $headers);

        $this->client->request(
            $method,
            $resource,
            $method === Request::METHOD_GET ? $data : [],
            [],
            $headers,
            ($method !== Request::METHOD_GET && $data) ? json_encode($data) : null
        );

        return $this->client->getResponse();
    }

    protected function sendFormDataRequest(
        string $resource,
        array $data = [],
        array $files = [],
        array $headers = [],
        string $apiToken = null
    ): Response {
        $headers = array_merge([
            'CONTENT_TYPE' => 'multipart/form-data',
            'HTTP_AUTHORIZATION' => $apiToken ? 'Bearer '.$apiToken : '',
        ], $headers);

        $this->client->request(
            'POST',
            $resource,
            $data,
            $files,
            $headers
        );

        return $this->client->getResponse();
    }
}
