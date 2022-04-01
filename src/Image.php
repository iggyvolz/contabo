<?php

namespace iggyvolz\contabo;

final class Image
{
    public function __construct(
        public readonly string $imageId,
        public readonly string $tenantId,
        public readonly string $customerId,
        public readonly string $name,
        public readonly string $description,
        public readonly string $url,
        public readonly int $sizeMb,
        public readonly int $uploadedSizeMb,
        public readonly string $osType,
        public readonly string $version,
        public readonly string $format,
        public readonly string $status,
        public readonly bool $standardImage,
        public readonly \DateTimeInterface $creationDate,
        public readonly \DateTimeInterface $lastModifiedDate,
        /** @param list<Tag> */
        public readonly array $tags,
    )
    {
    }
}