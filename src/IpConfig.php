<?php

namespace iggyvolz\contabo;

final class IpConfig
{
    public function __construct(
        public readonly IP $v4,
        public readonly IP $v6,
    )
    {
    }
}