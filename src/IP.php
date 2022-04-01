<?php

namespace iggyvolz\contabo;

final class IP
{
    public function __construct(
        public readonly string $ip,
        public readonly int $netmaskCidr,
        public readonly string $gateway,
    )
    {
    }
}