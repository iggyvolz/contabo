<?php

namespace iggyvolz\contabo;

use Ramsey\Uuid\UuidInterface;

final class Instance
{
    public function __construct(
        public readonly Client $client,
        public readonly string $tenantId,
        public readonly string $customerId,
        public readonly string $name,
        public readonly string $displayName,
        public readonly int $instanceId,
        public readonly string $region,
        public readonly string $productId,
        public readonly ?string $imageId,
        public readonly IpConfig $ipConfig,
        public readonly string $macAddress,
        public readonly int $ramMb,
        public readonly int $cpuCores,
        public readonly string $osType,
        public readonly int $diskMb,
        /** @param list<string> */
        public readonly array $sshKeys,
        public readonly \DateTimeInterface $createdDate,
        public readonly ?\DateTimeInterface $cancelDate,
        public readonly string $status,
        public readonly int $vHostId,
        /** @param list<array{id:int,quantity:int}> */
        public readonly array $addOns,
        public readonly string $productType,
    )
    {
    }

    public function reinstall(
        UuidInterface $imageId,
        ?array $sshKeys = null,
        ?int $rootPassword = null,
        ?string $userData = null,
    ): void {
        var_dump($this->client->execute("PUT", "/v1/compute/instances/" . $this->instanceId, "mixed", array_filter([
            "imageId" => $imageId->toString(),
            "sshKeys" => $sshKeys,
            "rootPassword" => $rootPassword,
            "userData" => $userData
        ], fn(mixed $x): bool => !is_null($x))));
    }
}