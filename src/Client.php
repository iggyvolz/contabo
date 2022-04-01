<?php

namespace iggyvolz\contabo;

use CuyZ\Valinor\Mapper\Source\JsonSource;
use CuyZ\Valinor\Mapper\TreeMapper;
use CuyZ\Valinor\MapperBuilder;
use iggyvolz\contabo\Pagination\Page;
use iggyvolz\contabo\Pagination\Paginator;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Ramsey\Uuid\Uuid;

final class Client
{

    private const BASE_URL = "https://api.contabo.com";
    /** @internal */
    public readonly TreeMapper $mapper;

    private function __construct(
        private readonly string $accessToken,
        private readonly ClientInterface $client,
        private readonly RequestFactoryInterface $requestFactory,
        private readonly StreamFactoryInterface $streamFactory,
    ) {
        $this->mapper = (new MapperBuilder())->registerConstructor(fn(): self => $this)->mapper();
    }

    /** @return Paginator<Instance> */
    public function listInstances(): Paginator
    {
        return new Paginator($this, "/v1/compute/instances", Instance::class);
    }

    /** @return Paginator<Image> */
    public function listImages(): Paginator
    {
        return new Paginator($this, "/v1/compute/images", Image::class);
    }

    public static function get(string $clientId, string $clientSecret, string $username, string $password, ClientInterface $client, RequestFactoryInterface $requestFactory, StreamFactoryInterface $streamFactory): self
    {
        $body = $streamFactory->createStream(http_build_query([
            "grant_type" => "password",
            "client_id" => $clientId,
            "client_secret" => $clientSecret,
            "username" => $username,
            "password" => $password
        ]));
        $body->rewind();
        $response = $client->sendRequest($requestFactory->createRequest(
            "POST",
            "https://auth.contabo.com/auth/realms/contabo/protocol/openid-connect/token"
        )->withBody($body)->withHeader("Content-Type", "application/x-www-form-urlencoded"));
        $accessToken = json_decode($response->getBody()->getContents(), true, flags: JSON_THROW_ON_ERROR)["access_token"];
        return new self($accessToken, $client, $requestFactory, $streamFactory);
    }

    /** @internal */
    public function execute(string $method, string $url, ?string $type, mixed $body = null): mixed
    {
        $request = $this->requestFactory->createRequest(
            $method,
            self::BASE_URL . $url
        )->withHeader("Authorization", "Bearer $this->accessToken")->withHeader("X-Request-Id", Uuid::uuid4()->toString());
        if($body !== null) {
            $body = $this->streamFactory->createStream(json_encode($body, JSON_THROW_ON_ERROR));
            $body->rewind();
            $request = $request->withBody($body)->withHeader("Content-Type", "application/json");
        }
        $response = $this->client->sendRequest($request);

        if($response->getStatusCode() !== 200 && $response->getStatusCode() !== 201) {
            throw new \RuntimeException($response->getStatusCode() . " " . $response->getReasonPhrase() . "\n\n" . $response->getBody()->getContents());
        }
        if(is_null($type)) {
            return null;
        } else {
            return $this->mapper->map($type, new JsonSource($response->getBody()->getContents()));
        }
    }
}